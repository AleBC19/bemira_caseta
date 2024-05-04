<?php
namespace Api\V1\Rest\Refresh;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\Stdlib\Parameters;
use Application\Library\Api\Request;
use Application\Library\Api\Token;
use Application\Library\Api\Resource;
use Application\Model\Security\Users\UsersTable;

class RefreshResource extends Resource
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
            
            // Validates that refresh token exists.
            $refreshToken = Request::getHeader('Refresh-Token');
            if(!$refreshToken) {
                return $this->getError(401, $this->translator->translate('refresh-token faltante en el header'));
            }
            
            // Searches for the user whom the refresh token were assigned.
            $user = $this->sm->get(UsersTable::class)->selectByRefreshToken($refreshToken);
            
            // Validates if user with refresh token was found.
            if(!$user) {
                return $this->getError(401, $this->translator->translate('Refresh token not found'));
            }
            
            // Se valida el tiempo de expiración del refresh token.
            $horasTranscurridas = ((strtotime(date('Y-m-d H:i:s')) - strtotime($user->frmTokenCreationDatetimeUser))/60)/60;
            if($horasTranscurridas >= 12) {
                return $this->getError(401, $this->translator->translate('El refreshToken está expirado'));
            }
            
            // Payload is prepared.
            $payload = [
                'id' => $user->frmIdUsuario,
                'usuario' => $user->frmUsuarioNombreUsuario,
                'empleado' => trim(implode(' ', [$user->frmNombreEmpleado, $user->frmApellidoPaternoEmpleado, $user->frmApellidoMaternoEmpleado])),
                'iniciales' => substr($user->frmNombreEmpleado, 0, 1).substr($user->frmApellidoPaternoEmpleado, 0, 1)
            ];
            
            // Se asigna el accessToken.
            $body = [
                'accessToken' => Token::create($payload)
            ];
            
            // Se envía la respuesta.
            return $this->getResponse(201, $body);
            
        } catch (\Exception $e) {
            return $this->getError();
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
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
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
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
