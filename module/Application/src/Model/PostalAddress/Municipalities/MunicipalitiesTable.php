<?php
namespace Application\Model\PostalAddress\Municipalities;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Join;

class MunicipalitiesTable extends Table
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
     * Fetch all data.
     * @param array $params
     * @param \Countable $resultSetPrototype
     * @return \Countable
     */
    public function fetchAll($params = [])
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdMunicipality' => 'id',
            'frmNameMunicipality' => 'name',
            'frmStateIdMunicipality' => 'stated_id'
        ]);
        
        $select->order($this->asc('name'));
        
        return $this->selectCollection($this->tableGateway, $select, $params);
    }
    
}