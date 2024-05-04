<?php
namespace Application\Library\Model;

use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\ServiceManager;
use Laminas\I18n\Translator\Translator;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Update;
use Laminas\Db\Sql\Predicate\Predicate;
use Laminas\Db\Sql\Predicate\IsNull;
use Laminas\Db\Sql\Predicate\Operator;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\In;
use Laminas\Db\Sql\Predicate\NotIn;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Adapter\Driver\ConnectionInterface;
use Laminas\Db\Sql\Insert;

/**
 * Base class for table models.
 * @author workstation2
 *
 */
class Table
{
    
    /**
     * Service manager.
     * @var ServiceManager
     */
    protected $sm;
    
    /**
     * Objeto tablegateway para la tabla actual.
     * @var TableGateway
     */
    protected $tableGateway;
    
    /**
     * Objeto de la conexión real del gestor de base de datos usado;
     * @var ConnectionInterface
     */
    protected $connection;
    
    /** @var Translator */
    protected $translator;
    
    /**
     * Constructor.
     * @param TableGateway $tableGateway
     * @param ServiceManager $sm
     */
    public function __construct($tableGateway, $sm)
    {
        $this->sm = $sm;
        $this->tableGateway = $tableGateway;
        $this->connection = $this->tableGateway->getAdapter()->getDriver()->getConnection();
        $this->translator = new Translator();
    }
    
    /**
     * Returns a FQN from a column.
     * @param string $column
     * @param bool $unaccentLowercase
     * @return string
     */
    public function column($column, $unaccentLowercase = false)
    {
        $table = $this->tableGateway->table->getSchema() . '.' . $this->tableGateway->table->getTable();
        $fqn = $unaccentLowercase ? new Expression('lower(unaccent('.$table.'.'.$column.'))') : $table.'.'.$column;
        return $fqn;
    }
    
    /**
     * Returns a FQN from a column on UpperCase.
     * @param string $column
     * @param bool $upperCase
     * @return string
     */
    public function columnUpper($column, $upperCase = false)
    {
        $table = $this->tableGateway->table->getSchema() . '.' . $this->tableGateway->table->getTable();
        $fqn = $upperCase ? new Expression('upper('.$table.'.'.$column.')') : $table.'.'.$column;
        return $fqn;
    }
    
    /**
     * Returns a FQN from a column on UpperCase.
     * @param string $column
     * @param bool $upperCase
     * @return string
     */
    public function columnToFour($column, $addFour = true)
    {
        $string = "'0'";
        $table = $this->tableGateway->table->getSchema() . '.' . $this->tableGateway->table->getTable();
        $fqn = $addFour ? new Expression('LPAD('.$table.'.'.$column.', 4,'. $string .')') : $table.'.'.$column;
        return $fqn;
    }
    
    /**
     * Removes accents and converts to lower case a string.
     * @param string $string
     * @return string
     */
    public function unaccentLowercaseValue($string)
    {
        $newString = preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
        return mb_strtolower($newString, 'UTF-8');
    }
    
    /**
     * Creates a "on" pair for a join.
     * @param string $left
     * @param string $right
     * @return string
     */
    public function on($left, $right)
    {
        return $left.'='.$right;
    }
    
    /**
     * String for ascending order.
     * @param string $column
     * @return string
     */
    public function asc($column) {
        return $this->column($column).' asc';
    }
    
    /**
     * String for descending order.
     * @param string $column
     * @return string
     */
    public function desc($column) {
        return $this->column($column).' desc';
    }
    
    /**
     * Returns a predicates set represented by a expression string.
     * @param Parser $parser
     * @param TableGateway $tableGateway
     * @param string $select
     * @param int $index
     * @throws \Exception
     * @return \Laminas\Db\Sql\Predicate\Predicate
     */
    private function getPredicate(&$parser, &$tableGateway, &$select, &$index)
    {
        $predicate = new Predicate();
        while($index < count($parser->where)) {
            $expression = $parser->where[$index];
            if(!is_array($expression)) {
                switch($expression) {
                    case 'and':
                    case 'or':
                        $index++;
                        break;
                    case '(':
                        $combination = @$parser->where[$index-1] == 'and' ? Predicate::OP_AND : (@$parser->where[$index-1] == 'or' ? Predicate::OP_OR : null);
                        $index++;
                        $predicate->addPredicate($this->getPredicate($parser, $tableGateway, $select, $index), $combination);
                        break;
                    case ')':
                        $index++;
                        return $predicate;
                        break;
                }
            } else {
                // Gets column alias and extract the FQN.
                $fullColumnName = $parser->getFullyQualifiedColumnName(
                    $tableGateway->table,
                    $select->getRawState(Select::COLUMNS),
                    $expression['column']
                );
                
                // If column is not found in base table then is searched in join tables.
                if(!$fullColumnName) {
                    foreach($select->joins->getJoins() as $join) {
                        $fullColumnName = $parser->getFullyQualifiedColumnName(
                            $join['name'],
                            $join['columns'],
                            $expression['column']
                            );
                        if($fullColumnName) {
                            break;
                        }
                    }
                }
                // If column is not found, an exception is thrown.
                if(!$fullColumnName) {
                    throw new \Exception('No se pudo obtener el FQN de la columna'.' '.$expression['column']);
                }
                // According to the operator the corresponding method is invoked.
                switch($expression['operator']) {
                    case 'eq': // equal
                        if(strtolower($expression['value']) == 'null') {
                            $operator = new IsNull($fullColumnName);
                        } else {
                            $operator = new Operator($fullColumnName, Operator::OP_EQ, $expression['value']);
                        }
                        break;
                    case 'ne': // not equal
                        $operator = new Operator($fullColumnName, Operator::OP_NE, $expression['value']);
                        break;
                    case 'gt': // greater than
                        $operator = new Operator($fullColumnName, Operator::OP_GT, $expression['value']);
                        break;
                    case 'ge': // greater than or equal
                        $operator = new Operator($fullColumnName, Operator::OP_GTE, $expression['value']);
                        break;
                    case 'lt': // less than
                        $operator = new Operator($fullColumnName, Operator::OP_LT, $expression['value']);
                        break;
                    case 'le': // less than or equal
                        $operator = new Operator($fullColumnName, Operator::OP_LTE, $expression['value']);
                        break;
                    case 'li': // like
                        $like = new Like($fullColumnName, $this->unaccentLowercaseValue($expression['value']));
                        $operator = $like->setSpecification('lower(unaccent(CAST(%1$s AS VARCHAR))) ILIKE %2$s');
                        break;
                    case 'nl': // not like
                        $like = new Like($fullColumnName, $this->unaccentLowercaseValue($expression['value']));
                        $operator = $like->setSpecification('lower(unaccent(CAST(%1$s AS VARCHAR))) NOT ILIKE %2$s');
                        break;
                    case 'in': // in
                        $valueSet = explode(',', $expression['value']);
                        $operator = new In($fullColumnName, $valueSet);
                        break;
                    case 'ni': // not in
                        $valueSet = explode(',', $expression['value']);
                        $operator = new NotIn($fullColumnName, $valueSet);
                        break;
                }
                $combination = @$parser->where[$index-1] == 'and' ? Predicate::OP_AND : (@$parser->where[$index-1] == 'or' ? Predicate::OP_OR : null);
                $predicate->addPredicate($operator, $combination);
                $index++;
            }
        }
        return $predicate;
    }
    
    /**
     * Performs a select over a data collection.
     * @param TableGateway $tableGateway
     * @param Select $select
     * @param array $params
     * @param array $config
     * @return \Application\Library\Model\Result
     */
    protected function selectCollection($tableGateway, $select, $params = [], $config = [])
    {
        // Result object is instantiated.
        $result = new Result();
        
        // GET parameter are extracted.
        $parser = new Parser($params);
        
        // Filter expressions are extracted.
        if($parser->where) {
            $index = 0;
            $select->where->addPredicate($this->getPredicate($parser, $tableGateway, $select, $index));
        }
        
        // Ordering is applied.
        if($parser->order) {
            $select->reset(Select::ORDER);
            $select->order($parser->order);
        }
        
        
        // Row counting is performed, in order to allow datatable generate pagination.
        // Instead of loading all rows for counting, the counting is done from the database thorughout the count(),
        // which reduces memory consumption and processing time.
        $totalRecords = 0;
        if($select->getRawState(Select::GROUP)) {
            $select2 = clone $select;
            $select2->reset(Select::ORDER);
            $r = $tableGateway->selectWith($select2);
            $totalRecords = $r->count();
        } else {
            $select2 = clone $select;
            $select2->reset(Select::QUANTIFIER);
            $select2->reset(Select::COLUMNS);
            $select2->reset(Select::JOINS);
            $select2->reset(Select::ORDER);
            $countCols = [];
            $table = $select->getRawState(Select::TABLE);
            foreach($select->getRawState(Select::COLUMNS) as $k => $c) {
                if($c instanceof \Laminas\Db\Sql\Expression || $c instanceof \Laminas\Db\Sql\Predicate\Expression) {
                    $countCols[] = $c->getExpression();//.' as '.$k;
                } elseif($table instanceof TableIdentifier) {
                    $countCols[] = implode('.', [
                        $table->getSchema(),
                        $table->getTable(),
                        $c
                    ]);
                } else {
                    $countCols[] = $table.'.'.$c;
                }
            }
            foreach($select->joins->getJoins() as $join) {
                $select2->join($join['name'], $join['on'], [], $join['type']);
                if(@$join['name'] && is_array($join['name'])) {
                    foreach($join['columns'] as $c) {
                        $countCols[] = array_key_first($join['name']).'.'.$c;
                    }
                }
            }
            if($select->getRawState(Select::QUANTIFIER) == 'DISTINCT') {
                $select2->columns(['rows' => new Expression('count(distinct('.implode(',',$countCols).'))')]);
            } else {
                $select2->columns(['rows' => new Expression('count(*)')]);
            }
            $tg = new TableGateway($tableGateway->getTable(), $tableGateway->getAdapter());
            $r = $tg->selectWith($select2)->current();
            $totalRecords = $r->rows;
        }
        $result->totalRecords = (int) $totalRecords;
        
        // Offset is applied.
        if($parser->offset) {
            $select->offset($parser->offset);
        }
        
        // Limit is applied.
        if($parser->limit) {
            $select->limit($parser->limit);
        }
        
        // Data is obtained.
//         return $select->getSqlString();
        $sqlResult['data'] = $tableGateway->selectWith($select)->toArray();
        if(!$parser->select) {
            // Accesible columns are obtained from the query.
            $parser->select = [];
            $parser->select = array_merge($parser->select, array_keys($select->getRawState(Select::COLUMNS)));
            foreach($select->joins->getJoins() as $join) {
                $parser->select = array_merge($parser->select, array_keys($join['columns']));
            }
        }
        $data = [];
        foreach($sqlResult['data'] as $r) {
            $row = [];
            foreach(array_keys($r) as $aliasColumn) {
                if(in_array($aliasColumn, $parser->select)) {
                    $row[$aliasColumn] = $r[$aliasColumn];
                }
            }
            $data[] = $row;
        }
        $result->data = $data;
        
        return $result;
        
    }
    
    /**
     * Query data for a single entity.
     * @param TableGateway $tableGateway
     * @param Select $select
     * @return \Application\Library\Model\Result
     */
    protected function selectEntity($tableGateway, $select)
    {
        $result = new Result();
        $columns = [];
        $columns = array_merge($columns, array_keys($select->getRawState(Select::COLUMNS)));
        foreach($select->joins->getJoins() as $join) {
            $columns = array_merge($columns, array_keys($join['columns']));
        }
        $data = $tableGateway->selectWith($select)->current();
        foreach($data as $aliasColumn => $value) {
            if(in_array($aliasColumn, $columns)) {
                $result->data[$aliasColumn] = $value;
            }
        }
        return $result;
    }
    
    /**
     * Performs an insertion.
     * @param Insert $insert
     * @return mixed
     */
    protected function insertWith($insert)
    {
        $result = $this->tableGateway->insertWith($insert);
//         $this->bitacora['operacion'] = $this->translator->translate('Creación');
        $table = $this->tableGateway->table->getSchema().'.'.$this->tableGateway->table->getTable();
        $id = @$this->tableGateway->getAdapter()->getDriver()->getLastGeneratedValue($table.'_id_seq');
//         $row = $this->selectById($id);
//         if($row) {
//             $this->log($row);
//         }
        return $id;
    }

    /**
     * Performs a logic deletion.
     * @param int $id
     * @param Update $update
     */
    protected function deleteWithUpdate($id, $update)
    {
        $this->tableGateway->updateWith($update);
//         $row = $this->selectById($id);
//         $this->bitacora['operacion'] = $this->translator->translate('Eliminación');
//         $this->log($row);
    }
}