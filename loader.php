<?php

// common classes to preload as an optimization to bypass autoloader 

$corePreloads = [
    'Slim/src/ResolveCallableTrait.php',
    'Slim/src/MiddlewareAwareTrait.php',
    'Slim/src/Slim.php',
    'Slim/src/Interfaces/CollectionInterface.php',
    'Slim/src/Collection.php',
    'Slim/src/Http/Interfaces/EnvironmentInterface.php',
    'Slim/src/Http/Environment.php',
    'Slim/src/Http/Interfaces/HeadersInterface.php',
    'Slim/src/Http/Headers.php',
    'Slim/src/Http/Interfaces/RequestInterface.php',
    'Slim/src/Http/Request.php',
    'Slim/src/Http/Interfaces/ResponseInterface.php',
    'Slim/src/Http/Response.php',
    'Slim/src/Routing/Interfaces/RouterInterface.php',
    'Slim/src/Routing/Router.php',
    'Slim/src/Routing/Interfaces/RouteInterface.php',
    'Slim/src/Routing/Route.php',
    'Slim/src/Routing/Interfaces/RouteInvocationStrategyInterface.php',
    'Slim/src/Routing/RouteInvocationStrategy.php',
];

foreach( $corePreloads as $file )
{
	require_once 'src/' . $file;
}

// class autoloader

function autoloader( $classname )
{
    static $namespaces = [];

    if( is_string($classname) ) // real autoloader
    {
        $classfile = $classname;

        // if don't start with 'App', load in 'src' folder

        if( strpos($classname, 'App\\') === 0 )
        {
            $classfile = lcfirst($classfile); // first letter App/Class -> app/Class
        }
        else
        {
            // replace the leading namespace with the new one ; if any

            foreach( $namespaces as $namespace => $npath )
            {
                if( strpos($classname, $namespace) === 0 ) // start with
                {
                    $classfile = substr_replace($classname, $npath, 0, strlen($namespace));

                    break;
                }
            }

            $classfile = 'src/' . $classfile;
        }

        // set real path

        $classfile = str_replace(['\\', '_'], '/', $classfile); // PSR-0

        $classfile = dirname(__FILE__) . '/' . $classfile . '.php';

        // load class file

        if( file_exists($classfile) )
        {
            require_once $classfile;
        }
        else
        {
            throw new RuntimeException('[loader] "' . $classname . '" called, but "' . $classfile . '" does not exist.');
        }
    }
    elseif( is_array($classname) ) // func declaration
    {
        $namespaces = $classname;

        spl_autoload_register('autoloader');
    }
    else
    {
        throw new RuntimeException('[loader] Class name "' . $classname . '" is unknown.');
    }
}

