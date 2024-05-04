<?php
namespace Application\Library\Model;

/**
 * Base class for query results.
 * @author workstation2
 *
 */
class Result
{
    /**
     * Total records without counting limit and offset.
     * @var int
     */
    public $totalRecords;
    
    /**
     * Dataset with the extracted data.
     * @var array
     */
    public $data;
    
}