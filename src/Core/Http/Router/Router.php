<?php

declare(strict_types=1);

namespace App\Core\Http\Router;

use DI\Container;
use App\Core\Http\Message\Request;
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
     * @var string $url The requested URL.
     */
    private string $url;

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

    public function __construct(Request $request, Container $container)
    {
        $this->request = $request;
        $this->container = $container;

        $implode = explode('/', $this->request->getUri()->getPath());

        $this->url = $implode[3];
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
        $method = $this->request->getMethod();

        if (!isset($this->routes[$method])) {
            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach ($this->routes[$method] as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }

        throw new RouterException('No matching routes');
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
