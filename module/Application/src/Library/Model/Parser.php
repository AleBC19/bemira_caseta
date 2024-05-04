<?php
namespace Application\Library\Model;

use Laminas\Db\Sql\Where;
use Laminas\Db\Sql\Predicate\Predicate;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\TableIdentifier;

/**
 * Parse a URL from an API query.
 * @author workstation2
 */
class Parser 
{
    public $select;
    
    public $where;
    
    public $order;
    
    public $offset;
    
    public $limit;
    
    /**
     * Valid operators for where expression.
     * @var array
     */
    private $comparisonOperators = ['eq', 'ne', 'gt', 'ge', 'lt', 'le', 'li', 'nl', 'in', 'ni'];
    
    /**
     * Logic operators.
     * @var array
     */
    private $logicOperators = ['and', 'or'];
    
    /**
     * Grouping operators.
     * @var array
     */
    private $groupingOperators = ['(', ')'];
    
    /**
     * Constructor.
     * @param array $params
     */
    function __construct($params = [])
    {
        $this->select = $this->select(@$params['select']);
        $this->where = $this->where(@$params['where']);
        $this->order = $this->order(@$params['order']);
        $this->offset = $this->offset(@$params['offset']);
        $this->limit = $this->limit(@$params['limit']);
    }
    
    /**
     * Validates and returns a valid value for selected columns.
     * @param string $value
     * @throws \Exception
     * @return NULL|array
     */
    private function select($value = null)
    {
        $select = null;
        if($value) {
            $columns = explode('|', $value);
            if(!is_array($columns)) {
                throw new \Exception('El valor select no tiene el formato correcto');
            }
            $select = [];
            foreach($columns as $c) {
                $select[] = trim($c);
            }
        }
        return $select;
    }
    
    /**
     * Validates and returns a valid value for filtered data.
     * @param string $value
     * @throws \Exception
     * @return NULL|\Laminas\Db\Sql\Where
     */
    private function where($value = null)
    {
        $where = null;
        if($value) {
            $filters = explode('|', $value);
            if(!is_array($filters)) {
                throw new \Exception('El valor where no tiene el formato correcto');
            }
            $where = [];
            $i = 0;
            $logicOperator = null;
            do {
                // Validates if there is a grouping operator.
                if(in_array($filters[$i], $this->groupingOperators) || in_array($filters[$i], $this->logicOperators)) {
                    $where[] = $filters[$i];
                    $i++;
                } else {
                    // Expressions are read in three-element subgroups: column, operator, value.
                    $f = explode(' ', $filters[$i]);
                    if(!is_array($f)) {
                        throw new \Exception('Operador no válido en where: '.$filters[$i]);
                    }
                    $w = [
                        'column' => @trim($f[0]),
                        'operator' => mb_strtolower(@trim($f[1]), 'UTF-8'),
                        'value' => @trim($f[2])
                    ];
                    // If operator is not valid, exception is thrown.
                    if(!in_array($w['operator'], $this->comparisonOperators)) {
                        throw new \Exception('Operador de filtrado '.$w['operator'].' no válido para el elemento ' . $w['column']);
                    }
                    // Value is reconstructed because it can be a text with spaces.
                    unset($f[0]);
                    unset($f[1]);
                    $w['value'] = implode(' ', $f);
                    // Expression is assigned.
                    $where[] = $w;
                    $i++;
                }
            } while(@$filters[$i]); // While an operator (logic or grouping) exists, keep reading the string.
        }
        return $where;
    }
    
    /**
     * Validates and returns a value for ordering.
     * @param string $value
     * @throws \Exception
     * @return NULL|array
     */
    private function order($value = null)
    {
        $order = null;
        if($value) {
            $orders = explode('|', $value);
            if(!is_array($orders)) {
                throw new \Exception('El valor order no tiene el formato correcto');
            }
            $order = [];
            foreach($orders as $ord) {
                $o = explode(' ', $ord);
                if(!in_array(strtolower(@$o[1]), ['asc', 'desc'])) {
                    throw new \Exception('Valor de ordenamiento '.$o[1].' no válido para el elemento ' . $o[0]);
                }
                $order[$o[0]] = $o[1];
            }
        }
        return $order;
    }
    
    /**
     * Validates and returns a value for offset.
     * @param int $value
     * @throws \Exception
     * @return NULL|int
     */
    private function offset($value = null)
    {
        $offset = null;
        if($value) {
            if(!ctype_digit($value)) {
                throw new \Exception('El valor offset debe ser numérico');
            }
            $value = intval($value);
            if(!is_int($value)) {
                throw new \Exception('El valor offset debe ser un número entero');
            }
            if($value < 0) {
                throw new \Exception('El valor offset debe ser mayor o igual a 0');
            }
            $offset = $value;
        }
        return $offset;
    }
    
    /**
     * Validates and returns a value for limit.
     * @param int $value
     * @throws \Exception
     * @return NULL|int
     */
    private function limit($value = null)
    {
        $limit = null;
        if($value) {
            if(!ctype_digit($value)) {
                throw new \Exception('El valor limit debe ser numérico');
            }
            $value = intval($value);
            if(!is_int($value)) {
                throw new \Exception('El valor limit debe ser un número entero');
            }
            if($value < 1) {
                throw new \Exception('El valor limit debe ser mayor o igual a 1');
            }
            $limit = $value;
        }
        return $limit;
    }
    
    /**
     * Returns valid columns from those available in a query.
     * @param array $requestedColumns
     * @return array
     */
    public function getRequestedColumns($availableColumns = [])
    {
        $columns = [];
        foreach($availableColumns as $alias => $column) {
            if(in_array($alias, $this->select)) {
                $columns[$alias] = $column;
            }
        }
        return $columns;
    }
    
    /**
     * Returns the FQN of a column
     * @param string|TableIdentifier $table
     * @param array $columns
     * @param string $column
     * @return string
     */
    public function getFullyQualifiedColumnName($table, $columns = [], $column)
    {
        $fullColumnName = null;
        if($columns) {
            foreach($columns as $alias => $name) {
                // If column name corresponds to alias, then the FQN is obtained.
                if($alias == $column) {
                    // If it is an expression, it is obtained.
                    if($name instanceof Expression || $name instanceof \Laminas\Db\Sql\Predicate\Expression) {
                        $fullColumnName = $name;
                    } 
                    // If it is a column name, the FQN is built.
                    else if($table instanceof TableIdentifier) {
                        $fullColumnName = implode('.', [
                            $table->getSchema(),
                            $table->getTable(),
                            $name
                        ]);
                    } 
                    // If it is a subquery.
                    else if(is_array($table)){
                        $fullColumnName = implode('.', [
                            array_key_first($table),
                            $name
                        ]);
                    } 
                    // if it is a plain text table and column names.
                    else {
                        $fullColumnName = implode('.', [
                            $table,
                            $name
                        ]);
                    }
                    break;
                }
            }
        }
        return $fullColumnName;
    }
    
}