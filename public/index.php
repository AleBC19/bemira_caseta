<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-skeleton for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-skeleton/blob/master/LICENSE.md New BSD License
 */

use Laminas\ApiTools\Application;
use Laminas\Stdlib\ArrayUtils;
use Application\Library\Api\Request;
use Laminas\Db\Adapter\Adapter;

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Redirect legacy requests to enable/disable development mode to new tool
if (php_sapi_name() === 'cli'
    && $argc > 2
    && 'development' == $argv[1]
    && in_array($argv[2], ['disable', 'enable'])
) {
    // Windows needs to execute the batch scripts that Composer generates,
    // and not the Unix shell version.
    $script = defined('PHP_WINDOWS_VERSION_BUILD') && constant('PHP_WINDOWS_VERSION_BUILD')
        ? '.\\vendor\\bin\\laminas-development-mode.bat'
        : './vendor/bin/laminas-development-mode';
    system(sprintf('%s %s', $script, $argv[2]), $return);
    exit($return);
}

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

if (! file_exists('vendor/autoload.php')) {
    throw new RuntimeException(
        'Unable to load application.' . PHP_EOL
        . '- Type `composer install` if you are developing locally.' . PHP_EOL
        . '- Type `vagrant ssh -c \'composer install\'` if you are using Vagrant.' . PHP_EOL
        . '- Type `docker-compose run api-tools composer install` if you are using Docker.'
    );
}

// Setup autoloading
include 'vendor/autoload.php';

$appConfig = include 'config/application.config.php';

if (file_exists('config/development.config.php')) {
    $appConfig = ArrayUtils::merge(
        $appConfig,
        include 'config/development.config.php'
    );
//     error_reporting(E_ALL|E_STRICT);
//     ini_set('display_errors', 'on');
//     ini_set('memory_limit', -1);
}

// CORS and HTTP methods validation.
if(isset($_SERVER['HTTP_ORIGIN'])) {
    $allowedOrigins = $appConfig['environment']['api']['allowedOrigins'];
    $origin = parse_url($_SERVER['HTTP_ORIGIN']);
    if(!in_array($origin['host'], $allowedOrigins)) {
        exit();
    }
    header('Access-Control-Allow-Origin: '.$origin['scheme'].'://'.$origin['host']);
    header('Access-Control-Allow-Credentials: true');
}
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//     header('Access-Control-Allow-Headers: Accept, Authorization, Content-Type, Referer, User-Agent');
    header('Access-Control-Allow-Credentials: true');
    if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    }
    if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header('Access-Control-Allow-Headers: *');
    }
    exit();
}

// Validates that Affiliate header exists.
$affiliate = Request::getHeader('Affiliate');

// Session recovery.
$sessionToken = Request::getHeader('Session-Token');            // Extracts session ID from Session-Token header.
if(!$sessionToken) {
    $sessionToken = bin2hex(openssl_random_pseudo_bytes(13));   // A 26-char-length session ID is created.
}
session_id($sessionToken);                                      // Session ID is assigned.
session_name('sessionToken');                                   // Assigned session name.
ini_set('session.gc_maxlifetime', 12*60*60);                    // Assigned life time of session (12 hours like refresh tokens).
ini_set('session.use_strict_mode', 1);                          // Enabled session collision detection.
if(!session_start()) {                                          // If a collision is detected:
    $sessionToken = bin2hex(openssl_random_pseudo_bytes(16));   // A 32-char-length session ID is created.
    session_id($sessionToken);                                  // Session ID is assigned.
    session_start();                                            // Session is started.
}

// Gets affiliate database connection.
if(empty($_SESSION['db'])) {
    // $adapter = new Adapter([
    //     'driver'    => 'Pdo_Pgsql',
    //     'hostname'  => $appConfig['environment']['db']['hostname'],
    //     'port'      => $appConfig['environment']['db']['port'],
    //     'database'  => $appConfig['environment']['db']['database'],
    //     'username'  => $appConfig['environment']['db']['username'],
    //     'password'  => $appConfig['environment']['db']['password']
    // ]);
    // $result = $adapter->query('select "ip", "port", "database_name", "user", "password" from public.affiliates where "company_url" = ?', [$affiliate]);
    // $con = $result->current();
    $_SESSION['db'] = [
        'hostname'  => $appConfig['environment']['db']['hostname'],
        'port'      => $appConfig['environment']['db']['port'],
        'database'  => $appConfig['environment']['db']['database'],
        'username'  => $appConfig['environment']['db']['username'],
        'password'  => $appConfig['environment']['db']['password']
    ];
}

// Set default timezone.
date_default_timezone_set('America/Mexico_City');

// Run the application!
Application::init($appConfig)->run();
