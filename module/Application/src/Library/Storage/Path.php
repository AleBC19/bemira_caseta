<?php
namespace Application\Library\Storage;

/**
 * It defines the routes used for storing files for some resources in the app.
 */
class Path
{
    /**
     * Path for storing citizens files.
     * @var string
     */
    const CITIZENS = 'citizens/<id>/<fileName>';
        
    /**
     * Path for storing a file in the citizens folder.
     * @param int $id
     * @param string $fileName
     * @return mixed
     */
    public static function citizen($id, $fileName) {
        return str_replace(['<id>', '<fileName>'], [$id, $fileName], self::CITIZENS);
    }
    
}