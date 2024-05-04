<?php
namespace Application\Model\Security\Logs;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Insert;

class LogsTable extends Table
{
    /**
     * Constructor
     * @param TableGateway $tableGateway
     * @param ServiceManager $sm
     */
    public function __construct($tableGateway, $sm)
    {
        parent::__construct($tableGateway, $sm);
    }

    /**
     * Saves a log register.
     * @param Log $log
     */
    public function log($log)
    {
        $insert = new Insert($this->tableGateway->table);
        $insert->values([
            'user_id_creation' => $log->frmUserIdCreationLog,
            'user_ip' => $log->frmUserIpLog,
            'user_name' => $log->frmUserNameLog,
            'module' => $log->frmModuleLog,
            'operation' => $log->frmOperationLog,
            'data' => $log->frmDataLog,
            'creation_date' => $log->frmCreationDateLog,
            'creation_time' => $log->frmCreationTimeLog
        ]);
        $this->tableGateway->insertWith($insert);
    }
}