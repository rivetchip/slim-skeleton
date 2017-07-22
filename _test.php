<?php

$_iter = 300;

$_methods = ['GET','POST','PUT','DELETE'];
$_routes = require 'routes.php';

benchmark(function( $i ) use($_methods, $_routes) {

    // randomize
    $_method = $_methods[array_rand($_methods, 1)];
    $_route = $_routes[array_rand($_routes, 1)];
    list(,$_pattern,) = $_route;
    $_uri = preg_replace('#\([^\)]+\)#i', uniqid(), $_pattern);

    $_SERVER['REQUEST_METHOD'] = $_method;
    $_SERVER['REQUEST_URI'] = $_uri;

    require 'bootstrap.php';

}, $_iter);


function benchmark( $callable, $loop = 10 )
{
    $start_time = microtime(true);
    $start_memory = memory_get_usage();

    ob_start();
    for( $i=0; $i < $loop ; $i++ ) $callable($i);
    ob_end_clean();

    $end_time = microtime(true) - $start_time;
    $end_memory = memory_get_usage() - $start_memory;


    echo sprintf("Execution: %.7s seconds ( %s loops )", $end_time, $loop);

    $suffixes = ['', 'k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'];
    $factor = floor((strlen($end_memory) - 1) / 3);

    echo sprintf("Memory: %.3f %s bytes", $end_memory / pow(1024, $factor), $suffixes[$factor]);
}



