<?php 
namespace Application\Library\Api;

/**
 * It manages all API requests data.
 * @author workstation2
 */
class Request
{
    /**
     * Returns the value of a request header.
     * @param string $name
     * @return NULL|string
     */
    static function getHeader($name)
    {
        $header = null;
        if(isset($_SERVER[$name])) {
            $header = trim($_SERVER[$name]);
        } elseif(isset($_SERVER['HTTP_'.strtoupper(str_replace('-', '_', $name))])) {
            $header = trim($_SERVER['HTTP_'.strtoupper(str_replace('-', '_', $name))]);
        } elseif(function_exists('apache_request_headers')) {
            $header = @apache_request_headers()[$name];
        }
        return $header ? utf8_encode($header) : $header;
    }
}