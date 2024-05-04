<?php
namespace Application\Library\Storage;

interface StorageServiceInterface
{

    /**
     * Obtiene el estatus de conexión al servicio de almacenamiento.
     */
    public function isConnected();
    
    /**
     * Actualiza el access token de conexión al servicio de almacenamiento.
     */
    public function refreshAccessToken();
    
    /**
     * Devuelve el directorio raiz concatenado con la ruta especificada.
     * 
     * @param string $path            
     * @return string
     */
    public function getFullPath($path);

    /**
     * Obtiene una vista las imágenes o archivos.
     *
     * @param string $pathToFile            
     * @param string $default            
     * @return string
     */
    public function getThumbnail($pathToFile, $default);
    
    /**
     * Devuelve un enlace para la descarga del archivo.
     * @param string $pathToFile
     */
    public function getDownloadLink($pathToFile);

    /**
     * Carga un archivo.
     * 
     * @param mixed $binary            
     * @param string $pathToFile            
     * @return bool
     */
    public function fileUpload($binary, $pathToFile);

    /**
     * Descarga un archivo.
     * 
     * @param string $pathToFile            
     * @return bool
     */
    public function fileDownload($pathToFile);

    /**
     * Elimina un archivo del servidor de almacenamiento.
     * 
     * @param string $pathToFile            
     * @return bool
     */
    public function fileDelete($pathToFile);

    /**
     * Verifica si un archivo existe en el servidor de almacenamiento.
     * 
     * @param string $pathToFile            
     * @return bool
     */
    public function fileExists($pathToFile);

    /**
     * Crea un nuevo directorio.
     * 
     * @param string $directory            
     * @return bool
     */
    public function directoryCreate($directory);

    /**
     * Verifica si un direcotrio existe en el servidor de almacenamiento.
     * 
     * @param string $directory            
     * @return bool
     */
    public function directoryExists($directory);
}