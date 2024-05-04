<?php
namespace Application\Model\Config\Parameters;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Update;

class ParametersTable extends Table
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
            'frmIdParameter' => 'id',
            'frmCodeParameter' => 'code',
            'frmDescriptionParameter' => 'description',
            'frmActiveParameter' => 'active'
        ]);
        
        $select->order($this->asc('code'));
        
        return $this->selectCollection($this->tableGateway, $select, $params);
    }

    /**
     * Indicates if a parameter is active.
     * @param string $code
     * @return bool
     */
    public function isActive($code)
    {
        $select = new Select($this->tableGateway->table);
        $select->columns([
            'frmActiveParameter' => 'active'
        ]);
        $select->where->equalTo($this->column('code'), $code);
        $parameter = $this->tableGateway->selectWith($select)->current();
        return $parameter->frmActiveParameter == 't';
    }
}