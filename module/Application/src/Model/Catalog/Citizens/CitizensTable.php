<?php
namespace Application\Model\Catalog\Citizens;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Join;
use Application\Model\Security\Users\UsersTable;
use Application\Model\PostalAddress\Neighborhoods\NeighborhoodsTable;
use Application\Model\PostalAddress\ZipCodes\ZipCodesTable;
use Laminas\Db\Sql\Update;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Expression;

class CitizensTable extends Table
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
            'frmIdCitizen' => 'id',
            'frmVoterIdCitizen' => $this->columnUpper('voter_id', true),
            'frmNameCitizen' => $this->columnUpper('name', true),
            'frmLastNameCitizen' => $this->columnUpper('last_name', true),
            'frmMaternalSurnameCitizen' => $this->columnUpper('maternal_surname', true),
            'frmEmailAddressCitizen' => 'email_address',
            'frmPhoneCitizen' => 'phone',
            'frmDateOfBirthCitizen' => 'date_of_birth',
            'frmCellPhoneCitizen' => 'cell_phone',
            'frmNeighborhoodIdCitizen' => 'neighborhood_id',
            'frmStreetCitizen' => $this->columnUpper('street', true),
            'frmOutdoorNumberCitizen' => $this->columnUpper('outdoor_number', true),
            'frmInteriorNumberCitizen' => $this->columnUpper('interior_number', true),
            'frmSignatureCitizen' => 'signature',
            'frmFrontIneCitizen' => 'front_ine',
            'frmBackIneCitizen' => 'back_ine',
            'frmHiddenCitizen' => 'hidden',
            'frmActiveCitizen' => 'activate',
            'frmSectionNameCitizen' => $this->columnToFour('section_name')
        ]);
        
        $usersTable = $this->sm->get(UsersTable::class);
        $select->join($usersTable->tableGateway->table,
            $this->on($this->column('id'), $usersTable->column('citizen_id')), [
                'frmIdUser' => 'id',
                'frmUserNameUser' => 'user_name'
        ], Join::JOIN_LEFT);
        
        $neighborhoodsTable = $this->sm->get(NeighborhoodsTable::class);
        $select->join($neighborhoodsTable->tableGateway->table,
            $this->on($this->column('neighborhood_id'), $neighborhoodsTable->column('id')), [
                'frmNameNeighborhood' => 'name'
            ], Join::JOIN_LEFT);
        
        $zipCodesTable = $this->sm->get(ZipCodesTable::class);
        $select->join($zipCodesTable->tableGateway->table,
            $this->on($neighborhoodsTable->column('zip_code_id'), $zipCodesTable->column('id')), [
                'frmNameZipCode' => 'name'
            ], Join::JOIN_LEFT);
        
        $select->order($this->asc('name'));
        $select->order($this->asc('last_name'));
        $select->order($this->asc('maternal_surname'));
        
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
            'frmIdCitizen' => 'id',
            'frmNameCitizen' => $this->columnUpper('name', true),
            'frmLastNameCitizen' => $this->columnUpper('last_name', true),
            'frmMaternalSurnameCitizen' => $this->columnUpper('maternal_surname', true),
            'frmEmailAddressCitizen' => 'email_address',
            'frmSignatureCitizen' => 'signature',
            'frmFrontIneCitizen' => 'front_ine',
            'frmBackIneCitizen' => 'back_ine',
            'frmSectionNameCitizen' => $this->columnToFour('section_name')
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
                'job_position_id' => $data['frmJobPositionIdCitizen'],
                'voter_id' => $data['frmVoterIdCitizen'],
                'name' => $data['frmNameCitizen'],
                'last_name' => $data['frmLastNameCitizen'],
                'maternal_surname' => $data['frmMaternalSurnameCitizen'],
                'phone' => $data['frmPhoneCitizen'],
                'cell_phone' => $data['frmCellPhoneCitizen'],
                'email_address' => $data['frmEmailAddressCitizen'],
                'date_of_birth' => $data['frmDateOfBirthCitizen'],
                'neighborhood_id' => $data['frmNeighborhoodIdCitizen'],
                'street' => $data['frmStreetCitizen'],
                'outdoor_number' => $data['frmOutdoorNumberCitizen'],
                'interior_number' => $data['frmInteriorNumberCitizen'],
                'signature' => $data['frmSignatureCitizen'],
                'front_ine' => $data['frmFrontIneCitizen'],
                'back_ine' => $data['frmBackIneCitizen'],
                'user_id_creation' => $_SESSION['user']['id'],
                'creation_date' => date('Y-m-d'),
                'creation_time' => date('H:i:s'),
                'section_name' => $data['frmSectionNameCitizen']
            ]);
            $id = $this->insertWith($insert);
            
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
                'voter_id' => $data['frmVoterIdCitizen'],
                'name' => $data['frmNameCitizen'],
                'last_name' => $data['frmLastNameCitizen'],
                'maternal_surname' => $data['frmMaternalSurnameCitizen'],
                'phone' => $data['frmPhoneCitizen'],
                'cell_phone' => $data['frmCellPhoneCitizen'],
                'email_address' => $data['frmEmailAddressCitizen'],
                'date_of_birth' => $data['frmDateOfBirthCitizen'],
                'neighborhood_id' => $data['frmNeighborhoodIdCitizen'],
                'street' => $data['frmStreetCitizen'],
                'outdoor_number' => $data['frmOutdoorNumberCitizen'],
                'interior_number' => $data['frmInteriorNumberCitizen'],
                'signature' => $data['frmSignatureCitizen'],
                'front_ine' => $data['frmFrontIneCitizen'],
                'back_ine' => $data['frmBackIneCitizen'],
                'user_id_modification' => $_SESSION['user']['id'],
                'modification_date' => date('Y-m-d'),
                'modification_time' => date('H:i:s'),
                'section_name' => $data['frmSectionNameCitizen']
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
     * Select by ID.
     * @param int $id
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function selectById($id)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdCitizen' => 'id',
            'frmNameCitizen' => 'name',
            'frmLastNameCitizen' => 'last_name',
            'frmMaternalSurnameCitizen' => 'maternal_surname',
            'frmEmailAddressCitizen' => 'email_address'
        ]);
        
        $select->where->equalTo($this->column('id'), $id);
        
        return $this->tableGateway->selectWith($select)->current();
    }
    
    /**
     * Select by voter ID.
     * @param int $id
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function selectByVoterId($voterId, $idOmit = null)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdCitizen' => 'id',
            'frmNameCitizen' => 'name',
            'frmLastNameCitizen' => 'last_name',
            'frmMaternalSurnameCitizen' => 'maternal_surname',
            'frmEmailAddressCitizen' => 'email_address'
        ]);
        
        $select->where->equalTo($this->column('voter_id', true), $voterId);
        if($idOmit) {
            $select->where->notEqualTo($this->column('id'), $idOmit);
        }
        
        return $this->tableGateway->selectWith($select)->current();
    }

    /**
     * Select by name.
     * @param string $name
     * @param string $lastName
     * @param string $maternalSurname
     * @param string $idOmit
     * @return mixed
     */
    public function selectByName($name, $lastName, $maternalSurname = null, $idOmit = null)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdCitizen' => 'id',
            'frmNameCitizen' => 'name',
            'frmLastNameCitizen' => 'last_name',
            'frmMaternalSurnameCitizen' => 'maternal_surname'
        ]);
        $select->where->equalTo($this->column('name', true), $this->unaccentLowercaseValue($name));
        $select->where->equalTo($this->column('last_name', true), $this->unaccentLowercaseValue($lastName));
        if(is_null($maternalSurname)) {
            $select->where->isNull($this->column('maternal_surname'));
        } else {
            $select->where->equalTo($this->column('maternal_surname', true), $this->unaccentLowercaseValue($maternalSurname));
        }
        if($idOmit) {
            $select->where->notEqualTo($this->column('id'), $idOmit);
        }
        return $this->tableGateway->selectWith($select)->current();
    }
}