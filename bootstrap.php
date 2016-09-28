<?php
use Slim\Slim;
use API\Middleware\JSON;
use API\Middleware\TokenOverBasicAuth;


ini_set("display_errors",1);
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

// Init application mode
if (empty($_ENV['SLIM_MODE'])) {
    $_ENV['SLIM_MODE'] = (getenv('SLIM_MODE'))
        ? getenv('SLIM_MODE') : 'development';
}


// Init and load configuration
$config = array();

$configFile = dirname(__FILE__) . '/config/'
    . $_ENV['SLIM_MODE'] . '.php';

if (is_readable($configFile)) {
    require_once $configFile;
} else {
    require_once dirname(__FILE__) . '/config/default.php';
}


// Create Application
$app = new \Slim\Slim(array(
    'debug' => false
));


// Parses JSON body
$app->add(new \Slim\Middleware\ContentTypes());

// JSON Middleware
$app->add(new API\Middleware\JSON('/api/v1'));

// Auth Middleware (outer)
$app->add(new API\Middleware\TokenOverBasicAuth(array('root' => '/api/v1')));
