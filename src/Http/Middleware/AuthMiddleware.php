<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Http\Message\Request;
use App\Core\Interfaces\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): callable
    {
        if (empty($request->getHeader('Authorization'))) {
            // die('Unauthorized');
            echo 'Unauthorized' . '<br>';
        }

        return $next;
    }
}
