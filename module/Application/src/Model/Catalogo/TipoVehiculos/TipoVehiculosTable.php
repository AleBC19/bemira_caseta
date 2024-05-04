<?php
namespace Application\Model\Catalogo\TipoVehiculos;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
//use Laminas\Db\Sql\Insert;
//use Laminas\Db\Sql\Delete;
//use Application\Model\Security\Menus\MenusTable;

class TipoVehiculosTable extends Table
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
     * @return \Application\Library\Model\Result
     */
    public function fetchAll($params = [])
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdTipoVehiculos' => 'id',
            'frmTipoVehiculos' => 'nombre_vehiculo'
        ]);
        
       
        $select->order($this->asc('id'));
        
        return $this->selectCollection($this->tableGateway, $select, $params);
    }
    
    /**
     * Returns all privileges of a profile.
     * @param int $id
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function selectByProfileId($id)
    {

    }
    
    /**
     * Creates a new resource.
     * @param array $data
     */
    public function insert($data)
    {
    }
    
    /**
     * Delete all privileges of a profile.
     * @param int $id
     */
    public function deleteByProfileId($id)
    {
       
    }
    
    
}