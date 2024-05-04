<?php
namespace Api\V1\Rest\Users;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\Stdlib\Parameters;
use Application\Library\Api\Resource;
use Application\Model\Security\Users\UsersTable;
use Application\Model\Security\UsersPrivileges\UsersPrivilegesTable;

class UsersResource extends Resource
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
            $r = $this->sm->get(UsersTable::class)->selectByName($data['frmUserNameUser']);
            if($r) {
                $message = [
                    $this->translator->translate('El usuario'),
                    $data['frmUserNameUser'],
                    $this->translator->translate('ya existe')
                ];
                return $this->getError(409, implode(' ', $message));
            }
            // Does not allow to create a user with superadmin profile if user is not superadmin.
            if($data['frmProfileIdUser'] == 1 && $_SESSION['user']['id'] != 1) {
                return $this->getError(403, $this->translator->translate('No tiene permisos para crear un usuario con perfil Superadmin'));
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
            
            $id = $this->sm->get(UsersTable::class)->create($data);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = [
                'id' => $id,
                'message' => $this->translator->translate('Usuario creado correctamente')
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
            $r = $this->sm->get(UsersTable::class)->selectById($id);
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
            
            $this->sm->get(UsersTable::class)->delete($id);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = [
                'id' => $id,
                'message' => $this->translator->translate('Usuario eliminado correctamente')
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
            
            $result = $this->sm->get(UsersTable::class)->fetch($id);
            if(!$result->data) {
                return $this->getError(404, $this->translator->translate('No se encontró el registro'));
            }
            $privileges = $this->sm->get(UsersPrivilegesTable::class)->selectByUserId($id);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            // Privileges added to the result.
            $result->data['frmPrivilegesUser'] = [];
            foreach($privileges as $p) {
                $result->data['frmPrivilegesUser'][] = $p->frmMenuIdUserPrivilege;
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
            
            // Omit the information of superadmin.
            // $params['where'] = 'frmIdUser ne 1|and|(|'.$params['where'].'|)|';
            
            /**********************
             * Action realization *
             **********************/
            
            $result = $this->sm->get(UsersTable::class)->fetchAll($params);
            
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
        try {
            
            /*************************
             * Parameters extraction *
             *************************/
            
            $data = $this->getFilteredData($data);
            
            /**********************
             * Custom validations *
             **********************/
            
            // Only superadmin can update superadmin user.
            if($id == 1 && $_SESSION['usuario']['id'] != 1) {
                return $this->getError(403, $this->translator->translate('No se puede actualizar el registro'));
            }
            // Validates if resource exists.
            $r = $this->sm->get(UsersTable::class)->selectById($id);
            if(!$r) {
                return $this->getError(404, $this->translator->translate('No se encontró el registro'));
            }
            
            /**************************
             * Input data preparation *
             **************************/
            
            /**********************
             * Log initialization *
             **********************/
            
            // Not log for security reasons with passwords.
            
            /**********************
             * Action realization *
             **********************/
            $this->sm->get(UsersTable::class)->patch($id, $data);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = [
                'id' => $id,
                'message' => $this->translator->translate('Usuario actualizado correctamente')
            ];
            
            /********************
             * Log finalization *
             ********************/
            
            /********************
             * Response sending *
             ********************/
            
            return $this->getResponse(204, $body);
            
        } catch (\Exception $e) {
            return $this->getServerError($e);
        }
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
            
            // Only superadmin can update superadmin user.
            if($id == 1 && $_SESSION['usuario']['id'] != 1) {
                return $this->getError(403, $this->translator->translate('No se puede actualizar el registro'));
            }
            // Validates if resource already exists.
            $r = $this->sm->get(UsersTable::class)->selectByName($data['frmUsuarioNombreUsuario'], $id);
            if($r) {
                $message = [
                    $this->translator->translate('El usuario'),
                    $data['frmUsuarioNombreUsuario'],
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
            $this->sm->get(UsersTable::class)->update($id, $data);
            
            /***************************
             * Output data preparation *
             ***************************/
            
            $body = [
                'id' => $id,
                'message' => $this->translator->translate('Usuario actualizado correctamente')
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
