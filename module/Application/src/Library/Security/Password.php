<?php
namespace Application\Library\Security;

use Laminas\Crypt\Password\Bcrypt;

/**
 * It manages password creation and verification.
 * @author workstation2
 */
class Password
{
    
    /** @var string */
    public const SALT = '%31eCcy0nez_';

    /**
     * Create an encrypted password.
     * @param string $password
     * @return string
     */
    static function create($password)
    {
        $bcrypt = new Bcrypt();
        return $bcrypt->create($password.self::SALT);
    }

    /**
     * Verify a password.
     * @param string $unencryptedPassword
     * @param string $encryptedPassword
     * @return boolean
     */
    static function verify($unencryptedPassword, $encryptedPassword)
    {
        $bcrypt = new Bcrypt();
        return $bcrypt->verify($unencryptedPassword.self::SALT, $encryptedPassword);
    }
}
