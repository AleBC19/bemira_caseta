<?php
namespace Application\Library\Storage;

use Laminas\ServiceManager\ServiceManager;
use Application\Library\Storage\Dropbox\Dropbox;
use Application\Model\Config\Dropbox\DropboxTable;

/**
 * It manages all data that is stored in a storage service.
 * @author workstation2
 */
class Storage
{

    /**
     * @var StorageServiceInterface
     */
    public $service;

    /**
     * Constructor.
     * @param ServiceManager $sm
     */
    public function __construct(ServiceManager $sm = null)
    {   
        $dropboxTable = $sm->get(DropboxTable::class);
        $config = $dropboxTable->selectById(1);
        $this->service = new Dropbox(
            $sm, 
            $config->frmAppKeyDropbox, 
            $config->frmAppSecretDropbox, 
            $config->frmRefreshTokenDropbox, 
            $config->frmAccessTokenDropbox, 
            $config->frmRootPathDropbox
        );
    }
    
    /**
     * Indicates if storage is connected.
     * @return boolean
     */
    public function isConnected()
    {
        return $this->service->isConnected();
    }
    
    /**
     * Download an image as base64.
     * @param string $pathToFile
     */
    public function getImageAsBase64($pathToFile)
    {
        return $this->service->getImageAsBase64($pathToFile);
    }
    
    /**
     * Download a file.
     * @param string $pathToFile
     * @param string $destination
     */
    public function fileDownload($pathToFile)
    {
        $this->service->fileDownload($pathToFile);
    }
    
    /**
     * Deletes a file.
     * @param string $pathToFile
     */
    public function fileDelete($pathToFile)
    {
        $this->service->fileDelete($pathToFile);
    }
    
    /**
     * Upload a file.
     * @param string $source
     * @param string $pathToFile
     */
    public function fileUpload($binary, $pathToFile)
    {
        $this->service->fileUpload($binary, $pathToFile);
    }
}
