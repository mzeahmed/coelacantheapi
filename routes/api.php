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

$request = new \App\Core\Http\Message\Request(
    $_SERVER['REQUEST_METHOD'], // Méthode HTTP
    new \App\Core\Http\Message\Uri($_SERVER['REQUEST_URI']),
    getallheaders(),
    new \App\Core\Http\Message\Stream(fopen('php://input', 'rb'))
);

$router = new \App\Core\Http\Router\Router($request, $container);

$router->get(API_BASE_SLUG . '/', function () {
    echo 'Welcome to Coelacanthe API v1';
});

$router->get(API_BASE_SLUG . '/users/:id', 'App\Http\Api\Controllers\UsersController@show')->with('id', '[0-9]+');

$router->post(API_BASE_SLUG . '/login', 'App\Http\Api\Controllers\SecurityController@login');

try {
    $router->run();
} catch (\App\Core\Exceptions\RouterException $e) {
    echo $e->getMessage();
}

$end = microtime(true);
$executionTime = ($end - $start);
// echo 'Total Execution Time: ' . $executionTime . ' seconds';
