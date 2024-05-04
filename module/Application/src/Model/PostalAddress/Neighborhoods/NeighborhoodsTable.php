<?php
namespace Application\Model\PostalAddress\Neighborhoods;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Join;
use Application\Model\PostalAddress\ZipCodes\ZipCodesTable;
use Application\Model\PostalAddress\States\StatesTable;
use Application\Model\PostalAddress\Municipalities\MunicipalitiesTable;
use Application\Model\PostalAddress\Cities\CitiesTable;

class NeighborhoodsTable extends Table
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
     * Fetch a collection.
     * @param array $params
     * @param \Countable $resultSetPrototype
     * @return \Countable
     */
    public function fetchAll($params = [])
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdNeighborhood' => 'id',
            'frmNameNeighborhood' => 'name'
        ]);
        
        $zipCodesTable = $this->sm->get(ZipCodesTable::class);
        $select->join($zipCodesTable->tableGateway->table,
            $this->on($this->column('zip_code_id'), $zipCodesTable->column('id')), [
                'frmNameZipCode' => 'name'
            ]);
        
        $statesTable = $this->sm->get(StatesTable::class);
        $select->join($statesTable->tableGateway->table,
            $this->on($zipCodesTable->column('state_id'), $statesTable->column('id')), [
                'frmNameState' => 'name'
            ], Join::JOIN_LEFT);
        
        $municipalitiesTable = $this->sm->get(MunicipalitiesTable::class);
        $select->join($municipalitiesTable->tableGateway->table,
            $this->on($zipCodesTable->column('municipality_id'), $municipalitiesTable->column('id')), [
                'frmNameMunicipality' => 'name'
            ], Join::JOIN_LEFT);
        
        $citiesTable = $this->sm->get(CitiesTable::class);
        $select->join($citiesTable->tableGateway->table,
            $this->on($zipCodesTable->column('city_id'), $citiesTable->column('id')), [
                'frmNameCity' => 'name'
            ], Join::JOIN_LEFT);
        
        $select->order($this->asc('name'));
        
        return $this->selectCollection($this->tableGateway, $select, $params);
    }
    
}