<?php
namespace Application\Library\Api;

use Laminas\ApiTools\Rest\AbstractResourceListener;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Http\Response;
use Laminas\Json\Json;
use Laminas\I18n\Translator\Translator;
use Application\Model\Security\Logs\Log;
use Application\Model\Security\Logs\LogsTable;

class Resource extends AbstractResourceListener
{
    /** @var ServiceManager */
    protected $sm;
    
    /** @var Translator */
    protected $translator;
    
    /** @var Log */
    protected $log;
    
    /**
     * Constructor.
     * @param ServiceManager $sm
     */
    function __construct($sm)
    {
        $this->sm = $sm;
        $this->translator = new Translator();
        $this->log = new Log();
    }
    
    /**
     * Returns filtered data from request, ommiting what is not needed.
     * @param array $data
     * @return mixed[]
     */
    function getFilteredData($data)
    {
        $values = [];
        // Get data.
        $filtered = $this->getInputFilter()->getValues();
        // Only existing data is asigned to use.
        foreach($data as $key => $value) {
            if(array_key_exists($key, $filtered)) {
                $values[$key] = $filtered[$key];
            }
        }
        return $values;
    }
    
    /**
     * Returns response from server.
     * @param int $code
     * @return \Laminas\Http\Response
     */
    function getResponse($code = Response::STATUS_CODE_200, $body = [], $headers = [])
    {
        $response = new Response();
        $response->setStatusCode($code);
        $response->getHeaders()->addHeaders([
            'Content-Type' => 'application/json'
        ]);
        if($body) {
            $body = Json::encode($body);
            $body = Json::prettyPrint($body, ['indent' => ' ']);
            $response->setContent($body);
        }
        return $response;
    }
    
    /**
     * Returns an error response.
     * @param int $code
     * @param string $message
     * @param array $details
     * @throws \Exception
     * @return \Laminas\Http\Response
     */
    function getError($code = Response::STATUS_CODE_500, $message = '', $details = [])
    {
        $response = new Response();
        $response->setStatusCode($code);
        $message = $message ? : $this->translator->translate('No se pudo realizar la acción');
        $body = [
            'error' => [
                'code' => $code,
                'message' => $message
            ]
        ];
        if($details) {
            if(!is_array($details)) {
                throw new \Exception($this->translator->translate('Los detalles de error deben ser de tipo arreglo'));
            }
            $body['error']['details'] = $details;
        }
        $body = Json::encode($body);
        $body = Json::prettyPrint($body, ['indent' => ' ']);
        $response->setContent($body);
        return $response;
    }
    
    /**
     * Devuelve un error del servidor.
     * @param \Exception $e
     * @return \Laminas\Http\Response
     */
    function getServerError($e)
    {
        return $this->getError(500, $e->getMessage());
    }

    /**
     * Starts the log flow.
     * @param int $id
     */
    function logStart($id = null)
    {
        // It creates the log object.
        $this->log = new Log();
        
        // It determines what function is calling (fetch, fetchAll, create, update, etc.)
        $callingFunction = debug_backtrace()[1]['function'];
        
        // Operation is identified.
        $operation = 'Desconocido';
        switch ($callingFunction) {
            case 'fetch':   $operation = 'Consulta'; break;
            case 'create':  $operation = 'Creación'; break;
            case 'patch':   $operation = 'Actualización'; break;
            case 'update':  $operation = 'Actualización'; break;
            case 'delete':  $operation = 'Eliminación'; break;
        }
        
        $this->log->frmUserIdCreationLog = $_SESSION['user']['id'];
        $this->log->frmUserIpLog = @$_SERVER['HTTP_CLIENT_IP'] ? : (@$_SERVER['HTTP_X_FORWARDED_FOR'] ? : (@$_SERVER['REMOTE_ADDR'] ? : '0.0.0.0'));
        $this->log->frmUserNameLog = $_SESSION['user']['citizen'];
        $this->log->frmModuleLog = Request::getHeader('Module');
        $this->log->frmOperationLog = $operation;
        
        // Retrieve the date before being updated, or eliminated.
        if($id && $callingFunction != 'fetch') {
            $data = json_decode($this->fetch($id)->getBody(), true)['data'];
            // Removes Base64 files.
            foreach($data as $k => $v) {
                if(str_contains($k, 'Base64')) {
                    unset($data[$k]);
                }
            }
        }
        switch($callingFunction) {
            case 'update':
            case 'patch':
                $this->log->frmDataLog['Before'] = $data;
                break;
            case 'delete':
                $this->log->frmDataLog = $data;
                break;
        }
    }
    
    /**
     * Finalizes the log flow.
     * @param int $id
     * @param array $data
     */
    function logEnd($id, $data = []) 
    {
        // It determines what function is calling (fetch, fetchAll, create, update, etc.)
        $callingFunction = debug_backtrace()[1]['function'];
        
        // Prepares the data to be saved.
        if($id && $callingFunction != 'fetch') {
            $data = json_decode($this->fetch($id)->getBody(), true)['data'];
        }
        // Removes Base64 files.
        foreach($data as $k => $v) {
            if(str_contains($k, 'Base64')) {
                unset($data[$k]);
            }
        }
        switch($callingFunction) {
            case 'update':
            case 'patch':
                $this->log->frmDataLog['After'] = $data;
                break;
            case 'fetch':
                $this->log->frmDataLog = $data;
                break;
            case 'create':
                $this->log->frmDataLog = $data;
                break;
        }
        
        $this->log->frmDataLog = json_encode($this->log->frmDataLog, JSON_PRETTY_PRINT);
        $this->log->frmCreationDateLog = date('Y-m-d');
        $this->log->frmCreationTimeLog = date('H:i:s');
        
        $this->sm->get(LogsTable::class)->log($this->log);
    }
}