<?php

use App\Core\Container;
use App\Core\Http\Router\Router;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\NotAuthMiddleware;

$container = null;

Container::initializeContainer($container);

$router = new Router($container);

$userRoutes = [
    $router->get(API_BASE_SLUG . '/users/:page', 'App\Http\Controllers\Api\UserController@index')->addParam('page'),
    $router->get(API_BASE_SLUG . '/user/:id', 'App\Http\Controllers\Api\UserController@show')->addParam('id'),
    $router->post(API_BASE_SLUG . '/logout', 'App\Http\Controllers\Api\SecurityController@logout'),
    $router->put(API_BASE_SLUG . '/user/:id/update', 'App\Http\Controllers\Api\UserController@update')->addParam('id'),
    $router->delete(API_BASE_SLUG . '/user/:id/delete', 'App\Http\Controllers\Api\UserController@delete')->addParam('id'),
    $router->get(API_BASE_SLUG . '/posts', 'App\Http\Controllers\Api\PostController@index'),
    $router->get(API_BASE_SLUG . '/post/:id', 'App\Http\Controllers\Api\PostController@show')->addParam('id'),
];

$guestRoutes = [
    $router->post(API_BASE_SLUG . '/user/create', 'App\Http\Controllers\Api\UsersController@create'),
    $router->post(API_BASE_SLUG . '/login', 'App\Http\Controllers\Api\SecurityController@login'),
];

$router->get(API_BASE_SLUG . '/', function () {
    echo 'Welcome to Coelacanthe API v1';
});

$router->addGroupMiddleware($userRoutes, [new AuthMiddleware()]);
$router->addGroupMiddleware($guestRoutes, [new NotAuthMiddleware()]);

$router->get('/', function () {
    echo '';
});

$router->run();
