<?php

declare(strict_types=1);

namespace App\Core\Router;

/**
 * Class Route
 *
 * This class represents a route in the application.
 *
 * @package App\Core\Router
 */
class Route {
    /**
     * The slug to match against the URL.
     *
     * @var string
     */
    private string $slug;

    /**
     * The controller to execute if the route matches.
     *
     * @var string
     */
    private string $controller;

    /**
     * The method to execute on the controller.
     *
     * @var string
     */
    private string $method;

    public function __construct(string $slug, string $controller, string $method = 'index') {
        $this->slug = $slug;
        $this->controller = $controller;
        $this->method = $method;
    }

    /**
     * Checks if the route matches the given URL.
     *
     * @param string $url The URL to match against.
     *
     * @return bool True if the route matches the URL, false otherwise.
     */
    public function match(string $url): bool {
        $pattern = str_replace('{id}', '(\d+)', $this->slug);

        return preg_match("~^$pattern$~", $url);
    }

    /**
     * Executes the conroller method associated with the route.
     *
     * @param string $url The URL associated with the route.
     *
     * @return mixed
     */
    public function execute(string $url): mixed {
        preg_match("~^{$this->slug}$~", $url, $matches);
        $params = isset($matches[1]) ? [$matches[1]] : [];
        $controller = new $this->controller();
        $method = $this->method;

        return $controller->$method(...$params);
    }
}
