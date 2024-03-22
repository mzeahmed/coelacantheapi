<?php

$router = new \App\Core\Http\Router\Router($_GET['url']);

$router->get(API_BASE_URL . '/', function () {
    echo 'Welcome to API v1';
});

$router->get(API_BASE_URL . '/users/:id', 'App\Http\Api\Controllers\UsersController@show')->with('id', '[0-9]+');

$router->post(API_BASE_URL . '/login', 'App\Http\Api\Controllers\SecurityController@login');

try {
    $router->run();
} catch (\App\Core\Exceptions\RouterException $e) {
    echo $e->getMessage();
}
