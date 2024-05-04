<?php
namespace Api\V1\Rest\Authentication;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\Stdlib\Parameters;
use Application\Library\Api\Resource;
use Application\Library\Security\Password;
use Application\Library\Api\Token;
use Application\Model\Security\Users\UsersTable;
use Laminas\Db\Adapter\Adapter;
use Application\Model\Security\UsersSections\UsersSectionsTable;

class AuthenticationResource extends Resource
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
            
            // Data is filtered and sanitized.
            $data = $this->getFilteredData($data);
            
            // Checked if user exists.
            $user = $this->sm->get(UsersTable::class)->selectByName($data['frmUserAuthentication'], null, false);
            if(empty($user->frmIdUser)) {
                return $this->getError(401, $this->translator->translate('El usuario o password es incorrecto'));
            }
            
            // Password given from the form is validated against stored password.
            if(!Password::verify($data['frmPasswordAuthentication'], $user->frmPasswordUser)) {
                return $this->getError(401, $this->translator->translate('El usuario o password es incorrecto'));
            }
            
            // Validates if user is active.
            if($user->frmActiveUser != 't') {
                return $this->getError(401, $this->translator->translate('El usuario está inactivo'));
            }
            
            // Data prepared.
            $payload = [
                'id' => $user->frmIdUser,
                'user' => $user->frmUserNameUser,
                'citizen' => trim(implode(' ', [$user->frmNameCitizen, $user->frmLastNameCitizen, $user->frmMaternalSurnameCitizen])),
                'initials' => substr($user->frmNameCitizen, 0, 1).substr($user->frmLastNameCitizen, 0, 1)
            ];
            
            // Access token is created.
            $accessToken = Token::create($payload);
            
            // Refresh token is created and stored in database.
            $refreshToken = bin2hex(openssl_random_pseudo_bytes(16));
            
            // Session ID is extracted and stored in database.
            $sessionToken = session_id();
            
            // Data stored in session.
            $_SESSION['user'] = $payload;
            
            // Tokens stored in database.
            $this->sm->get(UsersTable::class)->updateTokens($user->frmIdUser, $accessToken, $refreshToken);
            
            // Body is prepared for insert it in response.
            $body = [
                'accessToken' => $accessToken,
                'refreshToken' => $refreshToken,
                'sessionToken' => $sessionToken
            ];
            
            // Response is sent.
            return $this->getResponse(200, $body);
            
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
            $appConfig = $this->sm->get('ApplicationConfig');
            $adapter = new Adapter([
                'driver'    => 'Pdo_Pgsql',
                'hostname'  => $appConfig['environment']['db']['hostname'],
                'port'      => $appConfig['environment']['db']['port'],
                'database'  => $appConfig['environment']['db']['database'],
                'username'  => $appConfig['environment']['db']['username'],
                'password'  => $appConfig['environment']['db']['password']
            ]);
            $result = $adapter->query('select id from public.affiliates where company_url = ?', [$id]);
            if(!@$result->current()) {
                return $this->getError(404);
            }
            return $this->getResponse(200);
        } catch (Exception $e) {
            return $this->getError(500, $e->getMessage());
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
            
            // Access token payload is extracted.
            $body = Token::payload();
            
            // Response is sent.
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
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
