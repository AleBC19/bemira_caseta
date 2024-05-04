<?php
namespace Application\Library\Model;

/**
 * Base class for entity models.
 * @author workstation2
 *
 */
class Entity
{
    /**
     * Data exchange.
     * @param array $data
     */
    public function exchangeArray($data)
    {
        foreach($this->getArrayCopy() as $k => $v) {
            $this->$k = in_array(@$data[$k], [null, '']) ? null : $data[$k];
        }
    }
    
    /**
     * Returns a copy of all class properties with their values.
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}