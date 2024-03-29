<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Helpers\JSON;
use App\Core\Http\Message\Request;
use App\Core\Interfaces\MiddlewareInterface;

class NotAuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): callable
    {
        if ($request->getHeader('Authorization') !== null) {
            $header = $request->getHeader('Authorization');

            // if (empty($header)) {
            //     JSON::sendError(['message' => 'The token is not sent'], 401);
            // }

            if (isset($header['Authorization'])) {
                [$token] = sscanf($header['Authorization'], 'Bearer %s');
                if ($token) {
                    JSON::sendError(['message' => 'You must be logged out to access this resource'], 401);
                }
            }
        }

        return $next;
    }
}
