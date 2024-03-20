<?php

$router = new \App\Core\Router\Router();

$router->addRoute('/users', \App\Http\Controllers\UsersController::class, 'index');

\App\Core\Http\RequestHandler::handle($router);
