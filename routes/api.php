<?php

$start = microtime(true);

$container = null;
$containerBuilder = new \DI\ContainerBuilder();

try {
    $container = $containerBuilder->build();
} catch (Exception $e) {
    echo $e->getMessage();
}

\App\Core\Container::setContainer($container);

$router = new \App\Core\Http\Router\Router($_GET['url'], $container);

$router->get(API_BASE_URL . '/', function () {
    echo 'Welcome to Coelacanthe API v1';
});

$router->get(API_BASE_URL . '/users/:id', 'App\Http\Api\Controllers\UsersController@show')->with('id', '[0-9]+');

$router->post(API_BASE_URL . '/login', 'App\Http\Api\Controllers\SecurityController@login');

try {
    $router->run();
} catch (\App\Core\Exceptions\RouterException $e) {
    echo $e->getMessage();
}

$end = microtime(true);
$executionTime = ($end - $start);
// echo 'Total Execution Time: ' . $executionTime . ' seconds';
