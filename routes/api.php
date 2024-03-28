<?php

$start = microtime(true);

$container = null;

\App\Core\Container::initializeContainer($container);

$router = new \App\Core\Http\Router\Router($container);

$router->get(API_BASE_SLUG . '/', function () {
    echo 'Welcome to Coelacanthe API v1';
});

$router
    ->get(API_BASE_SLUG . '/users/:id', 'App\Http\Api\Controllers\UsersController@show')
    ->with('id', '[0-9]+')
    ->addMiddleware(new \App\Http\Middleware\AuthMiddleware());
$router
    ->get(API_BASE_SLUG . '/users', 'App\Http\Api\Controllers\UsersController@index')
    ->addMiddleware(new \App\Http\Middleware\AuthMiddleware());

$router->post(API_BASE_SLUG . '/login', 'App\Http\Api\Controllers\SecurityController@login');
$router->post(API_BASE_SLUG . '/logout', 'App\Http\Api\Controllers\SecurityController@logout');

try {
    $router->run();
} catch (\App\Core\Exceptions\RouterException $e) {
    echo $e->getMessage();
}

$end = microtime(true);
$executionTime = ($end - $start);
// echo 'Total Execution Time: ' . $executionTime . ' seconds';
