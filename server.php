<?php

require 'vendor/autoload.php';

use vennv\vapm\express\Express;

session_start();

$express = new Express();

$express->setPath(__DIR__ . '/website');

$express->use(function ($request, $response, $next) {
    echo 'Middleware 0' . PHP_EOL;
    return $next();
});

$express->use('/', function ($request, $response, $next) {
    echo 'Middleware 1' . PHP_EOL;
    return $next();
});

$express->use('/', function ($request, $response, $next) {
    echo 'Middleware 2' . PHP_EOL;
    return $next();
});

$express->use('/', function ($request, $response, $next) {
    echo 'Middleware 3' . PHP_EOL;
    return $next();
});

$express->get('/', function ($request, $response) {
    return $response->render('/index.php');
});

$express->post('/login', function ($request, $response) {
    return $response->redirect('/login.php');
});

$express->listen(8080, function () {
    echo 'Server is running on port 8080' . PHP_EOL;
});