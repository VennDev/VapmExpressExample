<?php

require 'vendor/autoload.php';

use vennv\vapm\express\Express;
use vennv\vapm\simultaneous\Async;

session_start();

$express = new Express();
$router = $express->router(['mergeParams' => true]);
$childRouter1 = $express->router(['mergeParams' => true]);
$childRouter2 = $express->router(['mergeParams' => true]);

$express->setPath(__DIR__ . '/website');

$express->use($express->static());

$express->use($express->json());

$childRouter2->get('/hello-name/:age', function ($request, $response) {
    $params = $request->params;
    return $response->send('Your name is ' . $params['name'] . ' and your age is ' . $params['age']);
});

$childRouter1->use('/info', $childRouter2);

$childRouter1->use('/hello-name/:name', function ($request, $response, $next) {
    return $next();
});

$express->use('/router', $childRouter1);

$express->post('/login', function ($request, $response) {
    return new Async(function () use ($request, $response) {
        Async::await($response->active('/index.php'));
        Async::await($response->redirect('/'));
    });
});

$express->get('/', function ($request, $response) {
    return $response->render('/index.php');
});

$express->get('/other', function ($request, $response) {
    return $response->render('/other.html');
});

// example: http://127.0.0.1:8080/router/get-name/Nam/info/get-age/16
$express->listen(8080, function () {
    echo 'Server is running on port 8080' . PHP_EOL;
});