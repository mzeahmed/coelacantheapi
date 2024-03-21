<?php

$router = new \App\Core\Http\Router\Router($_GET['url']);

$router->get('/', function () {
    echo 'Home';
});

// $router->get('/users/:id', 'Users@show')->with('id', '[0-9]+');
$router->get('/users/:id', 'Users@show')->with('id', '[0-9]+');


try {
    $router->run();
} catch (\App\Core\Exceptions\RouterException $e) {
    echo $e->getMessage();
}
