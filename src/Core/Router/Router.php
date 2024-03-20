<?php

namespace App\Core\Router;

use App\Core\Http\Response;

class Router {
    /**
     * The routes registered in the router
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Adds a route to the router.
     *
     * @param string $pattern The pattern to match against the URL.
     * @param string $controller The controller to execute if the route matches.
     * @param string $method The method to execute on the controller.
     *
     * @return void
     */
    public function addRoute(string $pattern, string $controller, string $method = 'index'): void {
        $route = new Route($pattern, $controller, $method);
        $this->routes[] = $route;
    }

    /**
     * Handles the incoming HTTP request.
     *
     * @param string $url The URL of the request.
     *
     * @return void
     */
    public function handleRequest(string $url): void {
        foreach ($this->routes as $route) {
            if ($route->match($url)) {
                $response = $route->execute($url);
                Response::sendJson($response);

                return;
            }
        }

        Response::sendJson(['error' => 'Route not found'], 404);
    }
}
