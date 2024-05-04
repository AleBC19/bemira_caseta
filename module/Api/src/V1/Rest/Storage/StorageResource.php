<?php
namespace Api\V1\Rest\Storage;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\Stdlib\Parameters;
use Application\Library\Api\Resource;
use Application\Model\Config\Dropbox\DropboxTable;
use Application\Library\Storage\Storage;
use Application\Library\Storage\Dropbox\Dropbox;

class StorageResource extends Resource
{
    /**
     * Constructor.
     * @param ServiceManager $sm
     */
    function __construct($sm)
    {
        parent::__construct($sm);
    }
    
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return new ApiProblem(405, 'The POST method has not been defined');
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        try {
            
            /*************************
             * Parameters extraction *
             *************************/
            
            /**********************
             * Custom validations *
             **********************/
            
            /**************************
             * Input data preparation *
             **************************/
            
            /**********************
             * Log initialization *
             **********************/
            
            $this->logStart($id);
            
            /**********************
             * Action realization *
             **********************/
            
            $result = $this->sm->get(DropboxTable::class)->fetch($id);
            if(!$result->data) {
                return $this->getError(404, $this->translator->translate('No se encontró el registro'));
            }
            
            /***************************
             * Output data preparation *
             ***************************/
            
            // Indicates if storage is connected.
            $result->data['frmConnectedStorage'] = $this->sm->get(Storage::class)->isConnected() ? 1 : 0;
            
            $body = $result;
            
            /********************
             * Log finalization *
             ********************/
            
            $this->logEnd($id, $body->data);
            
            /********************
             * Response sending *
             ********************/
            
            return $this->getResponse(200, $body);
            
        } catch (\Exception $e) {
            return $this->getServerError($e);
        }
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array|Parameters $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Patch (partial in-place update) a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patchList($data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        try {
            
            /*************************
             * Parameters extraction *
             *************************/
            
            $data = $this->getFilteredData($data);
            
            /**********************
             * Custom validations *
             **********************/
            
            // Validates if resource exists.
            $r = $this->sm->get(DropboxTable::class)->selectById($id);
            if(!$r) {
                return $this->getError(404, $this->translator->translate('No se encontró la configuración'));
            }
            
            /**************************
             * Input data preparation *
             **************************/
            
            // Retrieves the access and refresh tokens.
            $token = Dropbox::getToken(
                $data['frmAppKeyDropbox'],
                $data['frmAppSecretDropbox'],
                Dropbox::ACCESS_TOKEN,
                null,
                $data['frmAccessCodeDropbox']);
            if(@$token['errorToken']) {
                return $this->getError(400, $this->translator->translate('Error:').' '.$token['errorToken']);
            }
            $data['frmRefreshTokenDropbox'] = $token['refreshToken'];
            $data['frmAccessTokenDropbox'] = $token['accessToken'];
            
            /**********************
             * Log initialization *
             **********************/
            
            $this->logStart($id);
            
            /**********************
             * Action realization *
             **********************/
            
            $this->sm->get(DropboxTable::class)->update($id, $data);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = [
                'id' => $id,
                'message' => $this->translator->translate('Almacenamiento actualizado correctamente')
            ];
            
            /********************
             * Log finalization *
             ********************/
            
            $this->logEnd($id);
            
            /********************
             * Response sending *
             ********************/
            
            return $this->getResponse(204, $body);
            
        } catch (\Exception $e) {
            return $this->getServerError($e);
        }
    }
}
