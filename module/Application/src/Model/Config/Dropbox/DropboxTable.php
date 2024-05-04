<?php
namespace Application\Model\Config\Dropbox;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Update;

class DropboxTable extends Table
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
     * Fetch a resource.
     * @param int $id
     * @return \Application\Library\Model\Result
     */
    public function fetch($id)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdDropbox' => 'id',
            'frmAppKeyDropbox' => 'app_key',
            'frmAppSecretDropbox' => 'app_secret',
            'frmRootPathDropbox' => 'root_path',
            'frmRefreshTokenDropbox' => 'refresh_token',
            'frmAccessTokenDropbox' => 'access_token'
        ]);
        
        $select->where->equalTo($this->column('id'), $id);
        
        return $this->selectEntity($this->tableGateway, $select);
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
                'app_key' => $data['frmAppKeyDropbox'],
                'app_secret' => $data['frmAppSecretDropbox'],
                'root_path' => $data['frmRootPathDropbox'],
                'refresh_token' => $data['frmRefreshTokenDropbox'],
                'access_token' => $data['frmAccessTokenDropbox'],
                'user_id_modification' => $_SESSION['user']['id'],
                'modification_date' => date('Y-m-d'),
                'modification_time' => date('H:i:s')
            ]);
            $update->where->equalTo($this->column('id'), $id);
            $this->tableGateway->updateWith($update);
            
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * Update access token.
     * @param string $accesstoken
     * @return boolean
     */
    public function updateAccessToken($accessToken)
    {
        try {
            $update = new Update($this->tableGateway->table);
            $update->set([
                'access_token' => $accessToken
            ]);
            $update->where->equalTo('id', 1);
            $this->tableGateway->updateWith($update);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Select reseource by ID.
     * @param int $id
     * @return Dropbox
     */
    public function selectById($id)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmIdDropbox' => 'id',
            'frmAppKeyDropbox' => 'app_key',
            'frmAppSecretDropbox' => 'app_secret',
            'frmRootPathDropbox' => 'root_path',
            'frmRefreshTokenDropbox' => 'refresh_token',
            'frmAccessTokenDropbox' => 'access_token'
        ]);
        
        $select->where->equalTo($this->column('id'), $id);
        
        return $this->tableGateway->selectWith($select)->current();
    }
}