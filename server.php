<?php

require 'vendor/autoload.php';

use vennv\vapm\Async;
use vennv\vapm\express\Express;

session_start();

$express = new Express();
$router = $express->router();
$childRouter1 = $express->router();
$childRouter2 = $express->router();

$express->setPath(__DIR__ . '/website');

$express->use($express->static());

$express->use($express->json());

$childRouter1->get('/hello', function ($request, $response) {
    return $response->send('Hello World');
});

$childRouter1->get('/hello2', function ($request, $response) {
    return $response->send('Hello World 2');
});

$childRouter1->get('/hello-name/:name', function ($request, $response) {
    $name = $request->params->name;
    return $response->send('Hello World ' . $name);
});

$childRouter2->get('/hello', function ($request, $response) {
    return $response->send('Hello World');
});

$childRouter2->get('/hello2', function ($request, $response) {
    return $response->send('Hello World 2');
});

$childRouter2->get('/hello-name/:name', function ($request, $response) {
    $name = $request->params->name;
    return $response->send('Hello World ' . $name);
});

$childRouter1->use('/child2', $childRouter2);

$router->use('/child', $childRouter1);

$router->get('/hello', function ($request, $response) {
    return $response->send('Hello World');
});

$router->get('/hello2', function ($request, $response) {
    return $response->send('Hello World 2');
});

$router->get('/hello3', function ($request, $response) {
    return $response->send('Hello World 3');
});

$router->get('/test/:name', function ($request, $response) {
    $name = $request->params->name;
    return $response->send('Hello World ' . $name);
});

$express->use('/router', $router);

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