<?php

$loader = require 'loader.php';
$config = require 'config.php';

$helpers = require 'helpers.php';

autoloader([
    'Slim' => 'Slim/src'
]);

use Slim\Slim as SlimApp;

// cli special

if( PHP_SAPI == 'cli' )
{
    list($scriptName, $uri) = $argv; // "php /hello/john"

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = $uri;
    $_SERVER['HTTP_ACCEPT'] = 'text/plain';
}

// instantiate the app
$app = new SlimApp($config);

// register routes

$app->get('hello', '/hello/(\w+)', function( $request, $response, $name ) use( $app ) {


    return 'hello, '.$name;
});


// run the app, send the response
return $app->run();
