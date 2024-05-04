<?php
namespace Api\V1\Rest\Citizens;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\Stdlib\Parameters;
use Application\Library\Api\Resource;
use Application\Model\Catalog\Citizens\CitizensTable;
use Application\Library\Storage\Storage;
use Application\Library\Storage\Path;
use Application\Model\Catalog\Sections\SectionsTable;
use Application\Model\Config\Parameters\ParametersTable;

class CitizensResource extends Resource
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
        try {
            
            /*************************
             * Parameters extraction *
             *************************/
            
            $data = $this->getFilteredData($data);
            
            /**********************
             * Custom validations *
             **********************/
            
            // Validates if resource already exists.
            $r = $this->sm->get(CitizensTable::class)->selectByVoterId($data['frmVoterIdCitizen']);
            if($r) {
                $message = [
                    $this->translator->translate('La clave de elector'),
                    $data['frmVoterIdCitizen'],
                    $this->translator->translate('ya existe')
                ];
                return $this->getError(409, implode(' ', $message));
            }
            $r = $this->sm->get(CitizensTable::class)->selectByName($data['frmNameCitizen'], $data['frmLastNameCitizen'], $data['frmMaternalSurnameCitizen']);
            if($r) {
                $message = [
                    $this->translator->translate('El ciudadano'),
                    implode(' ', [$data['frmNameCitizen'], $data['frmLastNameCitizen'], $data['frmMaternalSurnameCitizen']]),
                    $this->translator->translate('ya existe')
                ];
                return $this->getError(409, implode(' ', $message));
            }

            // Validates if cell phone number is required.
            if(!$data['frmCellPhoneCitizen'] && $this->sm->get(ParametersTable::class)->isActive('CONF1')) {
                return $this->getError(409, 'El número de celular es requerido');
            }
            
            /**************************
             * Input data preparation *
             **************************/
            
            /**********************
             * Log initialization *
             **********************/
            
            $this->logStart();
            
            /**********************
             * Action realization *
             **********************/
            
            $id = $this->sm->get(CitizensTable::class)->create($data);
            
            // Send credential and signature images to cloud storage.
            $storage = $this->sm->get(Storage::class);
            if($data['frmSignatureCitizen']) {
                $binary = base64_decode(str_replace(' ', '+', explode(',', $data['frmSignatureBase64Citizen'])[1]));
                $storage->fileUpload($binary, Path::citizen($id, $data['frmSignatureCitizen']));
            }
            if($data['frmFrontIneCitizen']) {
                $binary = base64_decode(str_replace(' ', '+', explode(',', $data['frmFrontIneBase64Citizen'])[1]));
                $storage->fileUpload($binary, Path::citizen($id, $data['frmFrontIneCitizen']));
            }
            if($data['frmBackIneCitizen']) {
                $binary = base64_decode(str_replace(' ', '+', explode(',', $data['frmBackIneBase64Citizen'])[1]));
                $storage->fileUpload($binary, Path::citizen($id, $data['frmBackIneCitizen']));
            }
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = [
                'id' => $id,
                'message' => $this->translator->translate('Ciudadano creado correctamente')
            ];
            
            /********************
             * Log finalization *
             ********************/
            
            $this->logEnd($id);
            
            /********************
             * Response sending *
             ********************/
            
            return $this->getResponse(201, $body);
            
        } catch (\Exception $e) {
            return $this->getServerError($e);
        }
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
            
            // Only superadmin can access superadmin user.
            if($id == 1 && $_SESSION['user']['id'] != 1) {
                return $this->getError(403, $this->translator->translate('No tiene permisos para acceder al registro'));
            }
            
            /**************************
             * Input data preparation *
             **************************/
            
            /**********************
             * Log initialization *
             **********************/
            
            $this->logStart();
            
            /**********************
             * Action realization *
             **********************/
            
            $result = $this->sm->get(CitizensTable::class)->fetch($id);
            if(!$result->data) {
                return $this->getError(404, $this->translator->translate('No se encontró el registro'));
            }
            
            /***************************
             * Output data preparation *
             ***************************/
            
            // Download the signature and credential images.
            $storage = $this->sm->get(Storage::class);
            if($result->data['frmSignatureCitizen']) {
                $result->data['frmSignatureBase64Citizen'] = $storage->getImageAsBase64(Path::citizen($id, $result->data['frmSignatureCitizen']));
            }
            if($result->data['frmFrontIneCitizen']) {
                $result->data['frmFrontIneBase64Citizen'] = $storage->getImageAsBase64(Path::citizen($id, $result->data['frmFrontIneCitizen']));
            }
            if($result->data['frmBackIneCitizen']) {
                $result->data['frmBackIneBase64Citizen'] = $storage->getImageAsBase64(Path::citizen($id, $result->data['frmBackIneCitizen']));
            }
            
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
            
            // If not superadmin, then removes superadmin citizen from result.
            if($_SESSION['user']['id'] != 1) {
                $additionalWhere = 'frmIdCitizen ne 1';
                if(empty($params['where'])) {
                    $params['where'] = $additionalWhere;
                } else {
                    $params['where'] = $additionalWhere.'|and|(|'.$params['where'].'|)|';
                }
            }
            
            /**********************
             * Action realization *
             **********************/

            $result = $this->sm->get(CitizensTable::class)->fetchAll($params);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = $result;
            
            /********************
             * Response sending *
             ********************/
            
            return $this->getResponse(200, $body);
            
        } catch (\Exception $e) {
            return $this->getServerError($e);
        }
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
            
            // Superadmin citizen cannot be updated.
            if($id == 1) {
                return $this->getError(403, $this->translator->translate('No se puede actualizar el registro'));
            }
            // Validates if resource already exists.
            $r = $this->sm->get(CitizensTable::class)->selectByVoterId($data['frmVoterIdCitizen'], $id);
            if($r) {
                $message = [
                    $this->translator->translate('La clave de elector'),
                    $data['frmVoterIdCitizen'],
                    $this->translator->translate('ya existe')
                ];
                return $this->getError(409, implode(' ', $message));
            }
            $r = $this->sm->get(CitizensTable::class)->selectByName($data['frmNameCitizen'], $data['frmLastNameCitizen'], $data['frmMaternalSurnameCitizen'], $id);
            if($r) {
                $message = [
                    $this->translator->translate('El ciudadano'),
                    implode(' ', [$data['frmNameCitizen'], $data['frmLastNameCitizen'], $data['frmMaternalSurnameCitizen']]),
                    $this->translator->translate('ya existe')
                ];
                return $this->getError(409, implode(' ', $message));
            }
            $data['frmSectionNameCitizen'] = sprintf('%04d', $data['frmSectionNameCitizen']);
            $s = $this->sm->get(SectionsTable::class)->selectByNameFormated($data['frmSectionNameCitizen']);
            if(!$s)
            {
                $message = [
                    $this->translator->translate('La seccion'),
                    $data['frmSectionNameCitizen'],
                    $this->translator->translate('no existe')
                ];
                return $this->getError(409, implode(' ', $message));
            }
            // Validates if cell phone number is required.
            if(!$data['frmCellPhoneCitizen'] && $this->sm->get(ParametersTable::class)->isActive('CONF1')) {
                return $this->getError(409, 'El número de celular es requerido');
            }
            
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
            
            // Saves the citizen's data.
            $this->sm->get(CitizensTable::class)->update($id, $data);
            
            // Send credential and signature images to cloud storage.
            $storage = $this->sm->get(Storage::class);
            if($data['frmSignatureCitizen'] != $r->frmSignatureCitizen) {
                $binary = base64_decode(str_replace(' ', '+', explode(',', $data['frmSignatureBase64Citizen'])[1]));
                $storage->fileUpload($binary, Path::citizen($id, $data['frmSignatureCitizen']));
                $storage->fileDelete($binary, Path::citizen($id, $r->frmSignatureCitizen));
            }
            if($data['frmFrontIneCitizen'] != $r->frmFrontIneCitizen) {
                $binary = base64_decode(str_replace(' ', '+', explode(',', $data['frmFrontIneBase64Citizen'])[1]));
                $storage->fileUpload($binary, Path::citizen($id, $data['frmFrontIneCitizen']));
                $storage->fileDelete($binary, Path::citizen($id, $r->frmFrontIneCitizen));
            }
            if($data['frmBackIneCitizen'] != $r->frmBackIneCitizen) {
                $binary = base64_decode(str_replace(' ', '+', explode(',', $data['frmBackIneBase64Citizen'])[1]));
                $storage->fileUpload($binary, Path::citizen($id, $data['frmBackIneCitizen']));
                $storage->fileDelete($binary, Path::citizen($id, $r->frmBackIneCitizen));
            }
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = [
                'id' => $id,
                'message' => $this->translator->translate('Ciudadano actualizado correctamente')
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
