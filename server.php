<?php

require 'vendor/autoload.php';

use vennv\vapm\express\Express;
use vennv\vapm\simultaneous\Async;

session_start();

$express = new Express();
$app = $express->getApplication();

// This is a simple example of how to use the router
$router = $express->router(['mergeParams' => true]);
$childRouter1 = $express->router(['mergeParams' => true]);
$childRouter2 = $express->router(['mergeParams' => true]);

// Set the path to public folder of your website
$app->setPath(__DIR__ . '/website');
$app->use($app->static());
$app->use($app->json());

$childRouter2->get('/hello-name/:age', function ($request, $response) {
    $params = $request->params;
    return $response->send('Your name is ' . $params['name'] . ' and your age is ' . $params['age']);
});

$childRouter1->use('/info', $childRouter2);

$childRouter1->use('/hello-name/:name', function ($request, $response, $next) {
    return $next();
});

$app->use('/router', $childRouter1);

$app->post('/login', function ($request, $response) {
    return new Async(function () use ($request, $response) {
        Async::await($response->active('/index.php'));
        Async::await($response->redirect('/'));
    });
});

$app->get('/', function ($request, $response) {
    return $response->render('/index.php');
});

$app->get('/other', function ($request, $response) {
    return $response->render('/other.html');
});

// example: http://127.0.0.1:8080/router/get-name/Nam/info/get-age/16
$app->listen(8080, function () {
    echo 'Server is running on port 8080' . PHP_EOL;
});