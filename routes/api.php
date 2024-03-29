<?php

use App\Core\Container;
use App\Core\Http\Router\Router;
use App\Http\Middleware\AuthMiddleware;
use App\Core\Exceptions\RouterException;
use App\Http\Middleware\NotAuthMiddleware;

$container = null;

Container::initializeContainer($container);

$router = new Router($container);

$authRoutes = [
    $router->get(API_BASE_SLUG . '/users', 'App\Http\Api\Controllers\UsersController@index'),
    $router->get(API_BASE_SLUG . '/user/:id', 'App\Http\Api\Controllers\UsersController@show')->with('id', '[0-9]+'),
    $router->post(API_BASE_SLUG . '/logout', 'App\Http\Api\Controllers\SecurityController@logout'),
    $router->put(API_BASE_SLUG . '/user/:id/update', 'App\Http\Api\Controllers\UsersController@update')->with('id', '[0-9]+'),
    $router->delete(API_BASE_SLUG . '/user/:id/delete', 'App\Http\Api\Controllers\UsersController@delete')->with('id', '[0-9]+'),
];

$notAuthRoutes = [
    $router->post(API_BASE_SLUG . '/user/create', 'App\Http\Api\Controllers\UsersController@create'),
    $router->post(API_BASE_SLUG . '/login', 'App\Http\Api\Controllers\SecurityController@login'),
];

$router->get(API_BASE_SLUG . '/', function () {
    echo 'Welcome to Coelacanthe API v1';
});

$router->addGroupMiddleware($authRoutes, [new AuthMiddleware()]);
$router->addGroupMiddleware($notAuthRoutes, [new NotAuthMiddleware()]);

$router->get('/', function () {
    echo '';
});

try {
    $router->run();
} catch (RouterException $e) {
    echo $e->getMessage();
}
