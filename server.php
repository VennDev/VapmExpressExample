<?php

require 'vendor/autoload.php';

use vennv\vapm\Async;
use vennv\vapm\express\Express;

session_start();

$express = new Express();
$router = $express->router();

$router->get('/test', function ($request, $response) {
    return $response->send('Hello World');
});

$router->get('/test/:name', function ($request, $response) {
    $name = $request->params->name;
    return $response->send('Hello World ' . $name);
});

$express->setPath(__DIR__ . '/website');

$express->use($express->static());

$express->use($express->json());

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

$express->use('/', $router);

$express->get('/', function ($request, $response) {
    return $response->render('/index.php');
});

$express->get('/other', function ($request, $response) {
    return $response->render('/other.html');
});

$express->get('/get-list/:name', function ($request, $response) {
    $name = $request->params->name;
    $age = $request->query->Age;
    return $response->send('Hello World ' . $name . ' Age:' . $age);
});

$express->post('/login', function ($request, $response) {
    return new Async(function () use ($request, $response) {
        Async::await($response->active('/index.php'));
        Async::await($response->redirect('/'));
    });
});

$express->listen(8080, function () {
    echo 'Server is running on port 8080' . PHP_EOL;
});