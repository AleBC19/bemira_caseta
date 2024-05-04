<?php
namespace Application\Model\Security\Users;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
use Application\Library\Security\Password;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Update;
use Laminas\Db\Sql\Delete;
use Application\Model\Catalog\Citizens\CitizensTable;
use Application\Model\Security\UsersPrivileges\UsersPrivilegesTable;
use Laminas\Db\Sql\Join;

class UsersTable extends Table
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
            'frmIdUsuario' => 'id',
            'frmUsuarioNombreUsuario' => 'usuario_nombre',
            'frmUsuarioActivo' => 'activar'
        ]);
        
        $citizensTable = $this->sm->get(CitizensTable::class);
        $select->join($citizensTable->tableGateway->table,
            $this->on($this->column('empleado_id'), $citizensTable->column('id')), [
                'frmNombreEmpleado' => 'nombre',
                'frmApellidoPaternoEmpleado' => 'apellido_paterno',
                'frmApellidoMaternoEmpleado' => 'apellido_materno'
        ]);
        
        $select->order($this->asc('usuario_nombre'));
        
        return $this->selectCollection($this->tableGateway, $select, $params);
    }
    
    /**
     * Fetch a resource.
     * @param int $id
     * @return \Application\Library\Model\Result
     */
    public function fetch($id)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdUser' => 'id',
            'frmEmpleadoIdUsuario' => 'empleado_id',
            'frmPerfinIdUsuario' => 'perfil_id',
            'frmUsuarioNombreUsuario' => 'usuario_nombre'
        ]);
        
        $citizensTable = $this->sm->get(CitizensTable::class);
        $select->join($citizensTable->tableGateway->table,
            $this->on($this->column('empleado_id'), $citizensTable->column('id')), [
                'frmNombreEmpleado' => 'nombre',
                'frmApellidoPaternoEmpleado' => 'apellido_paterno',
                'frmApellidoMaternoEmpleado' => 'apellido_materno'
            ]);
        
        $select->where->equalTo($this->column('id'), $id);
        
        return $this->selectEntity($this->tableGateway, $select);
    }
    
    /**
     * Creates a resource.
     * @param array $data
     * @throws \Exception
     * @return mixed
     */
    public function create($data)
    {
        try {
            $this->connection->beginTransaction();
            
            $citizen = $this->sm->get(CitizensTable::class)->selectById($data['frmCitizenIdUser']);
            
            $insert = new Insert($this->tableGateway->table);
            $insert->values([
                'empleado_id' => $data['frmEmpleadoIdUsuario'],
                'perfil_id' => $data['frmPerfilIdUsuario'],
                'usuario_nombre' => $citizen->frmEmailAddressCitizen,
                'contrasenia' => Password::create($data['frmContraseniaUsusario']),
                'activar' => 't',
                'usuario_id_creacion' => $_SESSION['usuario']['id'],
                'fecha_creacion' => date('Y-m-d'),
                'hora_creacion' => date('H:i:s')
            ]);
            $id = $this->insertWith($insert);
            
            $usersPrivilegesTable = $this->sm->get(UsersPrivilegesTable::class);
            foreach($data['frmPrivilegiosUsuario'] as $privilege) {
                $usersPrivilegesTable->insert([
                    'frmUsuarioIdPrivilegioUsuario' => $id,
                    'frmMenuIdPrivilegioUsuario' => $privilege
                ]);
            }
            
            $this->connection->commit();
            return $id;
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * Updates a resource.
     * @param int $id
     * @param array $data
     * @throws \Exception
     * @return mixed
     */
    public function update($id, $data)
    {
        try {
            $this->connection->beginTransaction();
            
            $citizen = $this->sm->get(CitizensTable::class)->selectById($data['frmEmpleadoIdUsuario']);
            
            $update = new Update($this->tableGateway->table);
            $set = [
                'empleado_id' => $data['frmEmpleadoIdUsuario'],
                'perfil_id' => $data['frmPerfilIdUsuario'],
                'usuario_id_modificacion' => $_SESSION['usuario']['id'],
                'fecha_modificacion' => date('Y-m-d'),
                'hora_modificacion' => date('H:i:s')
            ];
            if($id != 1) {
                $set['usuario_nombre'] = $citizen->frmEmailAddressCitizen;
            }
            $update->set($set);
            $update->where->equalTo($this->column('id'), $id);
            $this->tableGateway->updateWith($update);
            
            $usersPrivilegesTable = $this->sm->get(UsersPrivilegesTable::class);
            $usersPrivilegesTable->deleteByUserId($id);
            foreach($data['frmPrivilegesUser'] as $privilege) {
                $usersPrivilegesTable->insert([
                    'frmUsuarioIdPrivilegioUsuario' => $id,
                    'frmMenuIdPrivilegioUsuario' => $privilege
                ]);
            }
            
            $this->connection->commit();
            return $id;
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * Patch a resource.
     * @param int $id
     * @param array $data
     * @throws \Exception
     * @return mixed
     */
    public function patch($id, $data)
    {
        try {
            $this->connection->beginTransaction();
            
            $update = new Update($this->tableGateway->table);
            $update->set([
                'password' => Password::create($data['frmContraseniaUsuario']),
                'usuario_id_modificacion' => $_SESSION['usuario']['id'],
                'fecha_modificacion' => date('Y-m-d'),
                'hora_modificacion' => date('H:i:s')
            ]);
            $update->where->equalTo($this->column('id'), $id);
            $this->tableGateway->updateWith($update);
            
            $this->connection->commit();
            return $id;
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * Deletes a resource. Attempts physical deletion, if not, a logic deletion is performed.
     * @param int $id
     */
    public function delete($id)
    {
        try {
            
            $this->connection->beginTransaction();
            
            $usersPrivilegesTable = $this->sm->get(UsersPrivilegesTable::class);
            $usersPrivilegesTable->deleteByUserId($id);
                        
            $delete = new Delete($this->tableGateway->table);
            $delete->where->equalTo($this->column('id'), $id);
            $this->tableGateway->deleteWith($delete);
            
            $this->connection->commit();
            
        } catch (\Exception $e) {
            
            $this->connection->rollback();
            
            $update = new Update($this->tableGateway->table);
            $update->set(['activar' => 'f']);
            $update->where->equalTo($this->column('id'), $id);
            $this->tableGateway->updateWith($update);
            
        }
    }
    
    /**
     * Select resource by ID.
     * @param int $id
     * @return User
     */
    public function selectById($id)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdUsuario' => 'id',
            'frmUsuarioNombreUsuario' => 'usuario_nombre',
            'frmContraseniaUsuario' => 'contrasenia',
            'frmUsuarioActivo' => 'activar',
        ]);
        
        $select->where->equalTo($this->column('id'), $id);
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
    /**
     * Select a resource by name.
     * @param string $name
     * @param int $omit
     * @return User
     */
    public function selectByName($name, $idOmit = null, $lowerCase = true)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdUsuario' => 'id',
            'frmUsuarioNombreUsuario' => 'usuario_nombre',
            'frmContraseniaUsuario' => 'contrasenia',
            'frmUsuarioActivo' => 'activar',
        ]);
        
        $citizensTable = $this->sm->get(CitizensTable::class);
        $select->join($citizensTable->tableGateway->table,
            $this->on($this->column('empleado_id'), $citizensTable->column('id')), [
                'frmNombreEmpleado' => 'nombre',
                'frmApellidoPaternoEmpleado' => 'apellido_paterno',
                'frmApellidoMaternoEmpleado' => 'apellido_materno'
            ]);
        
        $select->where->equalTo($this->column('usuario_nombre', $lowerCase), $name);
        if($idOmit) {
            $select->where->notEqualTo($this->column('id'), $idOmit);
        }
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
    /**
     * Select a resource by refresh token;
     * @param string $refreshToken
     * @return mixed
     */
    public function selectByRefreshToken($refreshToken)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdUsuario' => 'id',
            'frmUsuarioNombreUsuario' => 'usuario_nombre',
            'frmFechaHoraCreacionTokenUsuario' => 'fecha_hora_creacion_del_token'
        ]);
        
        $citizensTable = $this->sm->get(CitizensTable::class);
        $select->join($citizensTable->tableGateway->table,
            $this->on($this->column('empleado_id'), $citizensTable->column('id')), [
                'frmNombreEmpleado' => 'nombre',
                'frmApellidoPaternoEmpleado' => 'apellido_paterno',
                'frmApellidoMaternoEmpleado' => 'apellido_materno'
            ]);
        
        $select->where->equalTo($this->column('actualizar_token'), $refreshToken);
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
    /**
     * Updates user's tokens.
     * @param int $id
     * @param string $accessToken
     * @param string $refreshToken
     */
    public function updateTokens($id, $accessToken, $refreshToken = null)
    {
        $update = new Update($this->tableGateway->table);
        $update->set([
            'acceso_token' => $accessToken,
            'actualizar_token' => $refreshToken,
            'fecha_hora_creacion_del_token' => date('Y-m-d H:i:s')
        ]);
        $update->where->equalTo($this->column('id'), $id);
        $this->tableGateway->updateWith($update);
    }
    
}