<?php
namespace Api\V1\Rest\Parameters;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\Stdlib\Parameters;
use Application\Library\Api\Resource;
use Application\Model\Config\Parameters\ParametersTable;

class ParametersResource extends Resource
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
             * Action realization *
             **********************/
            
            $result = $this->sm->get(ParametersTable::class)->fetchAll($params);
            foreach($result->data as $k => $v) {
                $result->data[$k]['frmActiveParameter'] = $v['frmActiveParameter'] === true;
            }
            
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
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
