<?php

namespace App\Core\Http\Middleware;

use App\Core\Interfaces\MiddlewareInterface;

class MiddlewareStack implements MiddlewareInterface
{
    private array $middlewares = [];

    public function add(callable $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function handle($request, $response, callable $next)
    {
        if (empty($this->middlewares)) {
            return $next($request, $response);
        }

        $middleware = array_shift($this->middlewares);

        return $middleware($request, $response, function ($request, $response) use ($next) {
            return $this->handle($request, $response, $next);
        });
    }
}
