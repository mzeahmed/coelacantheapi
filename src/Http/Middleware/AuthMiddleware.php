<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Interfaces\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle($request, $response, callable $next)
    {
        if (empty($request->getHeader('Authorization'))) {
            return $response->withStatus(401);
        }

        return $next($request, $response);
    }
}
