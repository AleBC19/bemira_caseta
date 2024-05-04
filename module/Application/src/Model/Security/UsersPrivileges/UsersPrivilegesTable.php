<?php
namespace Application\Model\Security\UsersPrivileges;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Delete;
use Application\Model\Security\Menus\MenusTable;

class UsersPrivilegesTable extends Table
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
            'frmUsuarioIdPrivilegioUsuario' => 'usuario_id'
        ]);
        
        $menusTable = $this->sm->get(MenusTable::class);
        $select->join($menusTable->tableGateway->table,
            $this->on($this->column('menu_id'), $menusTable->column('id')), [
                'frmIdMenu' => 'id',
                'frmEtiquetaMenu' => 'etiqueta',
                'frmIconoMenu' => 'icono',
                'frmBotonMenu' => 'boton',
                'frmPadreMenu' => 'padre',
                'frmUrlMenu' => 'url',
                'frmOrdenMenu' => 'orden',
                'frmOperacionMenu' => 'operacion',
                'frmVisibleMenu' => 'visible'
            ]);
        
        $select->order($menusTable->asc('orden'));
        
        return $this->selectCollection($this->tableGateway, $select, $params);
    }
    
    /**
     * Returns all privileges of a user.
     * @param int $id
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function selectByUserId($id)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmMenuIdPrivilegioUsuario' => 'menu_id'
        ]);
        $select->where->equalTo($this->column('usuario_id'), $id);
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
            'usuario_id_creacion' => $_SESSION['usuario']['id'],
            'usuario_id' => $data['frmUsuarioIdPrivilegioUsuario'],
            'menu_id' => $data['frmMenuIdPrivilegioUsuario'],
            'fecha_creacion' => date('Y-m-d'),
            'hora_creacion' => date('H:i:s')
        ]);
        $this->tableGateway->insertWith($insert);
    }
    
    /**
     * Delete all privileges of a user.
     * @param int $id
     */
    public function deleteByUserId($id)
    {
        $delete = new Delete($this->tableGateway->table);
        $delete->where->equalTo($this->column('usuario_id'), $id);
        $this->tableGateway->deleteWith($delete);
    }
    
}