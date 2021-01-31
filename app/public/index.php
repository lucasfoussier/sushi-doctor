<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

if(!isset($_SERVER['APP_ENV'])){
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
} else {
    if($_SERVER['APP_ENV'] !== 'prod') {
        (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
    }
}

if(isset($_SERVER['APP_STAGE'])){
    define('APP_STAGE', $_SERVER['APP_STAGE']);
} else {
    throw new \Exception('APP_STAGE undefined');
}

if(isset($_SERVER['APP_NAME'])){
    define('APP_NAME', $_SERVER['APP_NAME']);
} else {
    throw new \Exception('APP_NAME undefined');
}

define('DYNAMODB_TABLE_PREFIX', APP_NAME.'.'.APP_STAGE.'.');


if ($_SERVER['APP_DEBUG']) {
    umask(0000);
    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
