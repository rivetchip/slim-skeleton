<?php

$word = '\w+';
$digit = '\d+';
$year = '(19|20)\d\d';
$all = '[^/]+';

return [
    'home' => ['get', '/', function($request, $response) {
        return 'home ok';
    }],

    'page' => ['get', "/page/($word)/($word)", '\App\Test@hello'],

    'page.post' => ['post', '/page/(\w+)/(\w+)/post', '\App\Test@postHello'],

    'invoke' => [['get','post'], '/invoke', \App\Test::class],

    'upload' => [['get','post'], '/upload', '\App\Test@upload'],

    'exception' =>['get', '/exception', '\App\Test@throwexception']
];

