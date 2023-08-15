<?php

/**
 * @throws Throwable
 */
function login(array $args) : string {
    $head = '
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <title>Login</title>
            </head>';

    if (isset($args['username']) && isset($args['password'])) {
        if ($args['username'] === 'admin' && $args['password'] === 'admin') {
            $_SESSION['username'] = $args['username'];
            $_SESSION['password'] = $args['password'];

            return $head . '
                <body>
                    <h1>Logged in!</h1>
                </body>
            </html>';
        }
    }

    return $head . '
                <body>
                    <h1>You are not logged in!</h1>
                </body>
            </html>';
}