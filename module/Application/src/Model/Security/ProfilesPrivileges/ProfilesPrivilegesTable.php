<?php
namespace Application\Model\Security\ProfilesPrivileges;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Delete;
use Application\Model\Security\Menus\MenusTable;

class ProfilesPrivilegesTable extends Table
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
            'frmProfileIdProfilePrivilege' => 'profile_id'
        ]);
        
        $menusTable = $this->sm->get(MenusTable::class);
        $select->join($menusTable->tableGateway->table,
            $this->on($this->column('menu_id'), $menusTable->column('id')), [
                'frmIdMenu' => 'id',
                'frmLabelMenu' => 'label',
                'frmIconMenu' => 'icon',
                'frmButtonMenu' => 'button',
                'frmParentMenu' => 'parent',
                'frmUrlMenu' => 'url',
                'frmOrderMenu' => 'order',
                'frmOperationMenu' => 'operation',
                'frmVisibleMenu' => 'visible'
            ]);
        
        $select->order($menusTable->asc('order'));
        
        return $this->selectCollection($this->tableGateway, $select, $params);
    }
    
    /**
     * Returns all privileges of a profile.
     * @param int $id
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function selectByProfileId($id)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmMenuIdProfilePrivilege' => 'menu_id'
        ]);
        $select->where->equalTo($this->column('profile_id'), $id);
        return $this->tableGateway->selectWith($select);
    }
    
    /**
     * Creates a new resource.
     * @param array $data
     */
    public function insert($data)
    {
        $insert = new Insert($this->tableGateway->table);
        $insert->values([
            'user_id_creation' => $_SESSION['user']['id'],
            'profile_id' => $data['frmProfileIdProfilePrivilege'],
            'menu_id' => $data['frmMenuIdProfilePrivilege'],
            'creation_date' => date('Y-m-d'),
            'creation_time' => date('H:i:s')
        ]);
        $this->tableGateway->insertWith($insert);
    }
    
    /**
     * Delete all privileges of a profile.
     * @param int $id
     */
    public function deleteByProfileId($id)
    {
        $delete = new Delete($this->tableGateway->table);
        $delete->where->equalTo($this->column('profile_id'), $id);
        $this->tableGateway->deleteWith($delete);
    }
    
    
}