<?php
namespace Application\Model\Security\Menus;

use Application\Library\Model\Table;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Sql\Select;

class MenusTable extends Table
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
            'frmIdMenu' => 'id',
            'frmEtiquetaMenu' => 'etiqueta',
            'frmPadreMenu' => 'padre',
            'frmOrdenMenu' => 'orden',
            'frmVisibleMenu' => 'visible'
        ]);
        
        $select->order($this->asc('padre'));
        $select->order($this->asc('orden'));
        
        return $this->selectCollection($this->tableGateway, $select, $params);
    }
    
}