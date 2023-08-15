<?php

/**
 * @throws Throwable
 */
function index(mixed $args = null) : string {
    if (is_array($args) && isset($args['username']) && isset($args['password'])) {
        if ($args['username'] === 'admin' && $args['password'] === 'admin') {
            $_SESSION['username'] = $args['username'];
            $_SESSION['password'] = $args['password'];
        }
    }

    if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
        return '
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <title>Home</title>
                </head>
                <body>
                    <h1>Home</h1>
                    <h2>Welcome, ' . $_SESSION['username'] . '!</h2>
                </body>
            </html>
        ';
    }

    return '
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <link rel="stylesheet" type="text/css" href="public/css/styles.css">
                <title>Home</title>
            </head>
            <body>
                <h1 id="h1">Home</h1>
                <form action="/login" method="post">
                    <input type="text" name="username" placeholder="Username">
                    <input type="password" name="password" placeholder="Password">
                    <button type="submit">Login</button>
                </form>
            </body>
        </html>
    ';
}