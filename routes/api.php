<?php

// $router = new \App\Core\Router\Router();
//
// $router->addRoute('/users', \App\Http\Controllers\UsersController::class, 'index');
//
// \App\Core\Http\RequestHandler::handle($router);

$router = new \App\Core\Router\Router($_GET['url']);

$router->get('/', function () {
    echo 'Home';
});

// $router->get('/users', function () {
//     echo json_encode(['name' => 'John Doe', 'email' => '', 'age' => 30]);
//     exit();
// });

$router->get('/users/:id', 'Users@show')->with('id', '[0-9]+');


try {
    $router->run();
} catch (\App\Core\Router\RouterException $e) {
    echo $e->getMessage();
}
