<?php
namespace Api\V1\Rest\Profiles;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\Stdlib\Parameters;
use Application\Library\Api\Resource;
use Application\Model\Security\Profiles\ProfilesTable;
use Application\Model\Security\ProfilesPrivileges\ProfilesPrivilegesTable;

class ProfilesResource extends Resource
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
            
            $r = $this->sm->get(ProfilesTable::class)->selectByName($data['frmNameProfile']);
            if($r) {
                $message = [
                    $this->translator->translate('El perfil'),
                    $data['frmNameProfile'],
                    $this->translator->translate('ya existe')
                ];
                return $this->getError(409, implode(' ', $message));
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
            
            $id = $this->sm->get(ProfilesTable::class)->create($data);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = [
                'id' => $id,
                'message' => $this->translator->translate('Perfil creado correctamente')
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
        try {
            
            /*************************
             * Parameters extraction *
             *************************/
            
            /**********************
             * Custom validations *
             **********************/
            
            // Superadmin cannot be deleted.
            if($id == 1) {
                return $this->getError(403, $this->translator->translate('No se puede eliminar el registro'));
            }
            // Validates if resource exists.
            $r = $this->sm->get(ProfilesTable::class)->selectById($id);
            if(!$r) {
                return $this->getError(404, $this->translator->translate('No se encontró el registro'));
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
            
            $this->sm->get(ProfilesTable::class)->delete($id);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = [
                'id' => $id,
                'message' => $this->translator->translate('Perfil eliminado correctamente')
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
            
            // Only superadmin can access superadmin profile.
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
            
            $result = $this->sm->get(ProfilesTable::class)->fetch($id);
            if(!$result->data) {
                return $this->getError(404, $this->translator->translate('No se encontró el registro'));
            }
            $privileges = $this->sm->get(ProfilesPrivilegesTable::class)->selectByProfileId($id);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            // Privileges added to the result.
            $result->data['frmPrivilegesProfile'] = [];
            foreach($privileges as $p) {
                $result->data['frmPrivilegesProfile'][] = $p->frmMenuIdProfilePrivilege;
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
            
            // If not superadmin, then removes superadmin profile from result.
            if($_SESSION['user']['id'] != 1) {
                $additionalWhere = 'frmIdProfile ne 1';
                if(empty($params['where'])) {
                    $params['where'] = $additionalWhere;
                } else {
                    $params['where'] = $additionalWhere.'|and|(|'.$params['where'].'|)|';
                }
            }
            
            /**********************
             * Action realization *
             **********************/
            
            $result = $this->sm->get(ProfilesTable::class)->fetchAll($params);
            
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
            
            // Superadmin cannot be deleted.
            if($id == 1) {
                return $this->getError(403, $this->translator->translate('No se puede actualizar el registro'));
            }
            // Validates if resource exists.
            // Validates if resource already exists.
            $r = $this->sm->get(ProfilesTable::class)->selectByName($data['frmNameProfile'], $id);
            if($r) {
                $message = [
                    $this->translator->translate('El perfil'),
                    $data['frmNameProfile'],
                    $this->translator->translate('ya existe')
                ];
                return $this->getError(409, implode(' ', $message));
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
            $this->sm->get(ProfilesTable::class)->update($id, $data);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = [
                'id' => $id,
                'message' => $this->translator->translate('Perfil actualizado correctamente')
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
