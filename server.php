<?php

require 'vendor/autoload.php';

use vennv\vapm\express\Express;

session_start();

$express = new Express();

$express->setPath(__DIR__ . '/website');

$express->get('/', function ($request, $response) {
    return $response->render('/index.php');
});

$express->post('/login', function ($request, $response) {
    return $response->redirect('/login.php');
});

$express->listen(8080, function () {
    echo 'Server is running on port 8080' . PHP_EOL;
});