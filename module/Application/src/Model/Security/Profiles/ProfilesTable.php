<?php
namespace Application\Model\Security\Profiles;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Update;
use Application\Model\Security\ProfilesPrivileges\ProfilesPrivilegesTable;

class ProfilesTable extends Table
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
            'frmIdProfile' => 'id',
            'frmNameProfile' => 'name',
            'frmActiveProfile' => 'activate'
        ]);
        
        $select->order($this->asc('name'));
        
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
            'frmIdProfile' => 'id',
            'frmNameProfile' => 'name'
        ]);
        
        $select->where->equalTo($this->column('id'), $id);
        
        return $this->selectEntity($this->tableGateway, $select);
    }
    
    /**
     * Creates resource.
     * @return mixed
     */
    public function create($data)
    {
        try {
            $this->connection->beginTransaction();
            
            $insert = new Insert($this->tableGateway->table);
            $insert->values([
                'name' => $data['frmNameProfile'],
                'activate' => 't',
                'user_id_creation' => $_SESSION['user']['id'],
                'creation_date' => date('Y-m-d'),
                'creation_time' => date('H:i:s')
            ]);
            $id = $this->insertWith($insert);
            
            $profilesPrivilegesTable = $this->sm->get(ProfilesPrivilegesTable::class);
            foreach($data['frmPrivilegesProfile'] as $privilege) {
                $profilesPrivilegesTable->insert([
                    'frmProfileIdProfilePrivilege' => $id,
                    'frmMenuIdProfilePrivilege' => $privilege
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
            
            $update = new Update($this->tableGateway->table);
            $update->set([
                'name' => $data['frmNameProfile'],
                'user_id_modification' => $_SESSION['user']['id'],
                'modification_date' => date('Y-m-d'),
                'modification_time' => date('H:i:s')
            ]);
            $update->where->equalTo($this->column('id'), $id);
            $this->tableGateway->updateWith($update);
            
            $profilesPrivilegesTable = $this->sm->get(ProfilesPrivilegesTable::class);
            $profilesPrivilegesTable->deleteByProfileId($id);
            foreach($data['frmPrivilegesProfile'] as $privilege) {
                $profilesPrivilegesTable->insert([
                    'frmProfileIdProfilePrivilege' => $id,
                    'frmMenuIdProfilePrivilege' => $privilege
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
     * Deletes a resource. Attempts physical deletion, if not, a logic deletion is performed.
     * @param int $id
     */
    public function delete($id)
    {
        try {
            
            $this->connection->beginTransaction();
            
            $profilesPrivilegesTable = $this->sm->get(ProfilesPrivilegesTable::class);
            $profilesPrivilegesTable->deleteByProfileId($id);
            
            $delete = new Delete($this->tableGateway->table);
            $delete->where->equalTo($this->column('id'), $id);
            $this->tableGateway->deleteWith($delete);
            
            $this->connection->commit();
            
        } catch (\Exception $e) {
            
            $this->connection->rollback();
            
            $update = new Update($this->tableGateway->table);
            $update->set(['activate' => 'f']);
            $update->where->equalTo($this->column('id'), $id);
            $this->tableGateway->updateWith($update);
            
        }
    }
    
    /**
     * Select reseource by ID.
     * @param int $id
     * @return Profile
     */
    public function selectById($id)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdProfile' => 'id',
            'frmNameProfile' => 'name'
        ]);
        
        $select->where->equalTo($this->column('id'), $id);
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
    /**
     * Select resource by name.
     * @param string $name
     * @param int $idOmit
     * @return Profile
     */
    public function selectByName($name, $idOmit = null)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdProfile' => 'id',
            'frmNameProfile' => 'name',
            'frmActiveProfile' => 'activate',
        ]);
        
        $select->where->equalTo($this->column('name', true), $this->unaccentLowercaseValue($name));
        if($idOmit) {
            $select->where->notEqualTo($this->column('id'), $idOmit);
        }
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
}