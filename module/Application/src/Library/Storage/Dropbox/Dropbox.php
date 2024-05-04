<?php
namespace Application\Library\Storage\Dropbox;

use Application\Library\Storage\StorageServiceInterface;
use Laminas\ServiceManager\ServiceManager;
use Application\Model\Config\Dropbox\DropboxTable;

class Dropbox implements StorageServiceInterface
{

    const ACCESS_TOKEN = 1;
    
    const REFRESH_TOKEN = 2;
    
    /**
     * Header size of reponse.
     * @var int
     */
    protected $headerSize;
    
    /**
     * La llave de la app.
     * 
     * @var string
     */
    protected $appKey;

    /**
     * La llave secreta de la app.
     * 
     * @var string
     */
    protected $appSecret;

    /**
     * @var string
     */
    protected $refreshToken;
    
    /**
     * El token de acceso de la app.
     * 
     * @var string
     */
    protected $accessToken;

    /**
     * El directorio raíz.
     * 
     * @var string
     */
    protected $rootDirectory;
    
    /**
     * @var ServiceManager
     */
    protected $sm;

    /**
     * Crea una instancia de la clase.
     * 
     * @param string $appKey            
     * @param string $appSecret            
     * @param string $accessToken            
     */
    public function __construct($sm, $appKey, $appSecret, $refreshToken, $accessToken, $rootDirectory = '')
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->refreshToken = $refreshToken;
        $this->accessToken = $accessToken;
        $this->rootDirectory = trim($rootDirectory, '/');
        $this->sm = $sm;
    }
    
    /**
     * Devuelve el long-lived refresh token para obtener access tokens.
     * @param string $appKey
     * @param string $appSecret
     * @param string $tipo
     * @param string $refreshToken
     * @return mixed
     */
    public static function getToken($appKey, $appSecret, $tipo, $refreshToken = null, $code = null)
    {
        if(!in_array($tipo, [self::REFRESH_TOKEN, self::ACCESS_TOKEN])) {
            return false;
        }
        $header = array(
            'Content-Type: multipart/form-data',
            'Authorization: Basic ' . base64_encode($appKey.':'.$appSecret)
        );
        if($tipo == self::ACCESS_TOKEN) {
            $params = [
                'code' => $code,
                'grant_type' => 'authorization_code'
            ];
        } elseif($tipo == self::REFRESH_TOKEN) {
            if(!$refreshToken) return;
            $params = [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken
            ];
        }
        $ch = curl_init('https://api.dropbox.com/oauth2/token');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response) {
            $result = json_decode($response, true);
            return [
                'refreshToken' => @$result['refresh_token'],
                'accessToken' => @$result['access_token'],
                'errorToken' => @$result['error_summary']
            ];
        } else {
            return [
                'errorToken' => 'No connection to Dropbox'
            ];
        }
    }
    
    /**
     * Realiza una ejecución de cURL.
     * @param array $params
     * @param boolean $retry
     * @return boolean|mixed|mixed|boolean
     */
    private function execute($params = [], $retry = true, $getHeaders = false)
    {
        $ch = curl_init($params['url']);
        if($getHeaders) {
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        if(@$params['header']) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $params['header']);
        }
        if(@$params['post']) {
            curl_setopt($ch, CURLOPT_POST, 1);
            if($params['post'] !== true) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params['post']);
            }
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        if($response) {
            $result = json_decode($response, true);
            if(@$result['error_summary'] && $retry == true) {
                if(strpos($result['error_summary'], 'expired_access_token') >= 0) {
                    $this->refreshAccessToken();
                    return $this->execute($params, false);
                }
                return false;
            }
            return $response;
        }
        return false;
    }
    
    /**
     * {@inheritDoc}
     * @see \Application\Library\Storage\StorageServiceInterface::isConnected()
     */
    public function isConnected()
    {
        try {         
            $response = $this->execute([
                'url' => 'https://api.dropboxapi.com/2/files/list_folder',
                'header' => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->accessToken
                ],
                'post' => json_encode([
                    'path' => ''
                ]),
            ], true);
            if ($response) {
                $result = json_decode($response, true);
                if (isset($result['error'])) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \Application\Library\Storage\StorageServiceInterface::refreshAccessToken()
     */
    public function refreshAccessToken()
    {
        $token = self::getToken($this->appKey, $this->appSecret, self::REFRESH_TOKEN, $this->refreshToken);
        if(@$token['accessToken']) {
            $this->sm->get(DropboxTable::class)->updateAccessToken(@$token['accessToken']);
        }
        $this->accessToken = @$token['accessToken'];
    }
    
    /**
     * Devuelve el directorio raiz concatenado con la ruta especificada.
     * 
     * {@inheritdoc}
     *
     * @see \Application\Library\Storage\StorageServiceInterface::getFullPath()
     */
    public function getFullPath($path = null)
    {
        $fullPath = '';
        if ($path) {
            $arrayPath = str_split($path);
            // if($arrayPath[0] == '/' || $arrayPath[0] == '\\') {
            // throw new \Exception('Path must not start with slashes');
            // } elseif ($arrayPath[count($arrayPath)-1] == '/' || $arrayPath[count($arrayPath)-1] == '\\') {
            // throw new \Exception('Path must not end with slashes');
            // }
            if ($this->rootDirectory) {
                $fullPath = '/' . $this->rootDirectory . '/' . $path;
            } else {
                $fullPath = '/' . $path;
            }
        }
        return $fullPath;
    }

    /**
     * Devuelve la imagen codificada en base64.
     * 
     * {@inheritdoc}
     *
     * @see \Application\Library\Storage\StorageServiceInterface::getThumbnail()
     */
    public function getThumbnail($pathToFile, $default = '')
    {
        try {
            $response = $this->execute([
                'url' => 'https://content.dropboxapi.com/2/files/get_thumbnail',
                'header' => [
                    'Content-Type: ',
                    'Authorization: Bearer ' . $this->accessToken,
                    'Dropbox-API-Arg: ' . json_encode([
                        'path' => $this->getFullPath($pathToFile),
                        'format' => 'png',
                        'size' => 'w1024h768'
                    ])
                ],
                'post' => true
            ]);
            if ($response) {
                $resultado = json_decode($response, true);
                if (isset($resultado['error'])) {
                    return $default;
                }
                return 'data:image/png;base64,' . base64_encode($response);
            } else {
                return $default;
            }
        } catch (\Exception $e) {
            
        }
    }
    
    /**
     * Devuelve un enlace para descargar el archivo.
     * @param string $pathToFile
     * @return string|mixed
     */
    public function getDownloadLink($pathToFile)
    {
        try {
            $response = $this->execute([
                'url' => 'https://api.dropboxapi.com/2/files/get_temporary_link',
                'header' => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->accessToken
                ],
                'post' => json_encode([
                    'path' => $this->getFullPath($pathToFile)
                ])
            ]);
            if ($response) {
                $result = json_decode($response, true);
                if (isset($result['error'])) {
                    return '';
                }
                return $result['link'];
            } else {
                return "#";
            }
        } catch (\Exception $e) {
            
        }
    }

    /**
     * Devuelve una imagen codificada en base64.
     * @param string $pathToFile
     * @param string $tmpDestination
     * @throws \Exception
     * @return string
     */
    public function getImageAsBase64($pathToFile)
    {
        try {
            $response = $this->execute([
                'url' => 'https://content.dropboxapi.com/2/files/download',
                'header' => [
                    'Content-Type: ',
                    'Authorization: Bearer ' . $this->accessToken,
                    'Dropbox-API-Arg: ' . json_encode([
                        'path' => $this->getFullPath($pathToFile)
                    ])
                ],
                'post' => true
            ], true);
            if ($response) {
                // Gets header and body.
//                 $header = substr($response, 0, $this->headerSize);
//                 $body = substr($response, $this->headerSize);
//                 $file = json_decode($header, true)['name'];
                $ext = pathinfo($pathToFile, PATHINFO_EXTENSION);
                // Encoded image.
                return 'data:image/'.$ext.';base64,'.base64_encode($response);
            }
            return '';
        } catch (\Exception $e) {
            
            return '';
        }
        
    }
    
    /**
     * Sube un archivo.
     * 
     * {@inheritdoc}
     *
     * @see \Application\Library\Storage\StorageServiceInterface::fileUpload()
     */
    public function fileUpload($binary, $pathToFile)
    {
        try {
            $response = $this->execute([
                'url' => 'https://content.dropboxapi.com/2/files/upload',
                'header' => [
                    'Content-Type: application/octet-stream',
                    'Authorization: Bearer ' . $this->accessToken,
                    'Dropbox-API-Arg: ' . json_encode([
                        'path' => $this->getFullPath($pathToFile),
                        'mode' => 'overwrite',
                        'autorename' => false,
                        'mute' => true
                    ])
                ],
                'post' => $binary
            ]);
            if ($response) {
                $result = json_decode($response, true);
                if (isset($result['error'])) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            
        }
    }

    /**
     * Descarga un archivo.
     * 
     * {@inheritdoc}
     *
     * @see \Application\Library\Storage\StorageServiceInterface::fileDownload()
     */
    public function fileDownload($pathToFile)
    {
        try {
            $response = $this->execute([
                'url' => 'https://content.dropboxapi.com/2/files/download',
                'header' => [
                    'Content-Type: ',
                    'Authorization: Bearer ' . $this->accessToken,
                    'Dropbox-API-Arg: ' . json_encode([
                        'path' => $this->getFullPath($pathToFile)
                    ])
                ],
                'post' => true
            ]);
            if ($response) {
                return base64_encode($response);
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
        
    }

    /**
     * Eliminar un archivo.
     * 
     * {@inheritdoc}
     *
     * @see \Application\Library\Storage\StorageServiceInterface::fileDelete()
     */
    public function fileDelete($pathToFile)
    {
        $response = $this->execute([
            'url' => 'https://api.dropboxapi.com/2/files/delete',
            'header' => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->accessToken
            ],
            'post' => json_encode([
                'path' => $this->getFullPath($pathToFile)
            ])
        ]);
        if ($response) {
            $result = json_decode($response, true);
            if (isset($result['error'])) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Cehca si un archivo o carpeta existe.
     * 
     * {@inheritdoc}
     *
     * @see \Application\Library\Storage\StorageServiceInterface::fileExists()
     */
    public function fileExists($pathToFile)
    {
        try {
            $response = $this->execute([
                'url' => 'https://api.dropboxapi.com/2/files/get_metadata',
                'header' => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->accessToken
                ],
                'post' => json_encode([
                    'path' => $this->getFullPath($pathToFile),
                    'include_media_info' => false,
                    'include_deleted' => false,
                    'include_has_explicit_shared_members' => false
                ])
            ]);
            if ($response) {
                $result = json_decode($response, true);
                if (isset($result['error'])) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            
        }
    }

    public function directoryCreate($directory)
    {
        return false;
    }

    public function directoryExists($directory)
    {
        return false;
    }

    
}