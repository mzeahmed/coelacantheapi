<?php

declare(strict_types=1);

namespace App\Core\Http\Router;

use DI\Container;
use App\Core\Http\Message\Uri;
use App\Core\Http\Message\Stream;
use App\Core\Http\Message\Request;
use App\Core\Http\Message\Response;
use App\Core\Exceptions\RouterException;

/**
 * Class Router
 *
 * The Router class is responsible for managing routes in the application.
 * It stores all the routes and handles the request by matching the requested URL with the registered routes.
 *
 * @package App\Core\Router
 */
class Router
{
    /**
     * @var Request $request The request object.
     */
    private Request $request;

    /**
     * @var array $routes The registered routes.
     */
    private array $routes = [];

    /**
     * @var Container $container The DI container instance.
     */
    private Container $container;

    /**
     * @var array $namedRoutes The registered named routes.
     */
    private array $namedRoutes = [];

    public function __construct(Container $container)
    {
        $this->request = new Request(
            $_SERVER['REQUEST_METHOD'],
            new Uri($_SERVER['REQUEST_URI']),
            getallheaders(),
            new Stream(fopen('php://input', 'rb'))
        );
        $this->container = $container;
    }

    /**
     * Adds routes to a specific middleware.
     *
     * @param array $routes The routes to add the middleware to.
     * @param array $middlewares The middlewares to add to the routes.
     *
     * @return void
     *
     * @example
     *      $router->addGroupMiddleware([$router->get('/users', 'UsersController@index')], [new AuthMiddleware()]);
     */
    public function addGroupMiddleware(array $routes, array $middlewares): void
    {
        foreach ($routes as $route) {
            foreach ($middlewares as $middleware) {
                $route->addMiddleware($middleware);
            }
        }
    }

    /**
     * Registers a GET route.
     *
     * @param string $path The path of the route.
     * @param mixed $callback The callback to be executed when the route is matched.
     * @param string|null $name The name of the route.
     *
     * @return Route The created route.
     */
    public function get(string $path, mixed $callback, string $name = null): Route
    {
        return $this->add($path, $callback, $name, 'GET');
    }

    /**
     * Registers a POST route.
     *
     * @param string $path The path of the route.
     * @param mixed $callback The callback to be executed when the route is matched.
     * @param string|null $name The name of the route.
     *
     * @return Route The created route.
     */
    public function post(string $path, mixed $callback, string $name = null): Route
    {
        return $this->add($path, $callback, $name, 'POST');
    }

    /**
     *  Handles the request by matching the requested URL with the registered routes.
     *
     * @return mixed The result of the route's callback.
     * @throws RouterException If no route matches the requested URL or if the request method is not supported.
     *
     */
    public function run(): mixed
    {
        $request = $this->request;

        try {
            $path = $request->getUri()->getPath();

            foreach ($this->routes[$request->getMethod()] as $route) {
                if ($route->match($path)) {
                    foreach ($route->getMiddlewares() as $middleware) {
                        $middleware->handle($request, function () use ($route, $request) {
                            return $route->call($request);
                        });
                    }

                    return $route->call($request);
                }
            }

            throw new RouterException('No matching routes');
        } catch (\Exception $e) {
            echo $e->getMessage();

            return new Response(
                new Stream(fopen('php://temp', 'rb+')),
                404,
                []
            );
        }
    }

    /**
     * Gets the URL of a named route.
     *
     * @param string $name The name of the route.
     * @param array $params The parameters to replace in the route's path.
     *
     * @return string The URL of the route.
     * @throws RouterException If no route matches the given name.
     *
     */
    public function url(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matches this name');
        }

        return $this->namedRoutes[$name]->getUrl($params);
    }

    /**
     * Registers a route.
     *
     * @param string $path The path to match against the URL.
     * @param mixed $callback The callback to execute if the route matches.
     * @param string|null $name The name of the route.
     * @param string $method The HTTP method for the route.
     *
     * @return Route The created route.
     */
    private function add(string $path, mixed $callback, ?string $name, string $method): Route
    {
        $route = new Route($path, $callback, $this->container);
        $this->routes[$method][] = $route;

        if (is_string($callback) && null === $name) {
            $name = $callback;
        }

        if ($name) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }
}
