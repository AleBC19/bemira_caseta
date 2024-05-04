<?php
namespace Application\Library\Api;

use Application\Library\Security\Password;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * It manages access token creation and reading.
 * @author workstation2
 */
class Token
{
    /**
     * Creates an access token.
     * @return string
     */
    static function create($payload)
    {
        $issuedAt = new \DateTimeImmutable();
        $info = [
            'iss' => 'Bemira',
            'iat' => $issuedAt->getTimestamp(),
            'exp' => $issuedAt->modify('+1 minutes')->getTimeStamp()
        ];
        $payload = array_merge($info, $payload);
        return JWT::encode($payload, Password::SALT, 'HS256');
    }
    
    /**
     * Extracts data from an access token.
     * @param string $accessToken
     * @throws \Exception
     * @return stdClass|array
     */
    static function payload($accessToken = null)
    {
        $authorization = [];
        
        // If not given accessToken then is extracted from Authorization header in the request.
        if(!$accessToken) {
            if(isset($_SERVER['Authorization'])) {
                $authorization = trim($_SERVER['Authorization']);
            } elseif(isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $authorization = trim($_SERVER['HTTP_AUTHORIZATION']);
            } elseif(function_exists('apache_request_headers')) {
                $authorization = @apache_request_headers()['Authorization'];
            }
            if(!$authorization) {
                throw new \Exception('No bearer token in Authorization header request');
            }
            $accessToken = @explode(' ', $authorization)[1];
        }
        
        // Se decodifica la información del accessToken.
        $decoded = JWT::decode($accessToken, new Key(Password::SALT, 'HS256'));
        
        return $decoded;
    }
}