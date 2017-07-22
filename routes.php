<?php

$word = '\w+';
$digit = '\d+';
$year = '(19|20)\d\d';
$all = '[^/]+';

return [
    'home' => ['get', '/slim-skeleton/', function($request, $response) {
        return 'home ok';
    }],

    'page' => ['get', "/slim-skeleton/page/($word)/($word)", '\App\Test@hello'],

    'page.post' => ['post', '/slim-skeleton/page/(\w+)/(\w+)/post', '\App\Test@postHello'],

    'invoke' => [['get','post'], '/slim-skeleton/invoke', \App\Test::class],

    'upload' => [['get','post'], '/slim-skeleton/upload', '\App\Test@upload']
];

