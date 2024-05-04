<?php
namespace Application\Model\PostalAddress\States;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Join;
use Application\Model\Catalog\Sections\SectionsTable;
use Application\Model\PostalAddress\Municipalities\MunicipalitiesTable;

class StatesTable extends Table
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
            'frmIdEstado' => 'id',
            'frmNombreEstado' => 'nombre'
        ]);
        
        $select->order($this->asc('nombre'));
        
        return $this->selectCollection($this->tableGateway, $select, $params);
    }

    /**
     * Fetch all states.
     * @param array $params
     * @param \Countable $resultSetPrototype
     * @return \Countable
     */
    public function fetchAllStatesSections($params = [])
    {
        $select = new Select($this->tableGateway->table);
        $select->quantifier(Select::QUANTIFIER_DISTINCT);
        $select->columns([
            'frmIdEstado' => 'id',
            'frmNombreEstado' => 'nombre'
        ]);
        
        $municipalitiesTable = $this->sm->get(MunicipalitiesTable::class);
        $select->join($municipalitiesTable->tableGateway->table, $this->on($this->column('id'), $municipalitiesTable->column('estado_id')), []);
        
        $sectionsTable = $this->sm->get(SectionsTable::class);
        $select->join($sectionsTable->tableGateway->table, $this->on($municipalitiesTable->column('id'), $sectionsTable->column('municipalidad_id')), []);
        
        $select->order($this->asc('nombre'));
        
        return $this->selectCollection($this->tableGateway, $select, $params);
    }
}