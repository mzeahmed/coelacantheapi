<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Helpers\JWT;
use App\Helpers\JSON;
use App\Core\Http\Message\Request;
use App\Core\Interfaces\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): callable
    {
        if ($request->getHeader('Authorization') !== null) {
            $header = $request->getHeader('Authorization');

            if (isset($header['Authorization'])) {
                [$token] = sscanf($header['Authorization'], 'Bearer %s');

                $decoded = JWT::decodeToken($token);
                $iss = $decoded->iss;

                if ($iss !== API_URL) {
                    JSON::sendError(['message' => 'The issuer do not match with the server'], 403);
                }

                if (!isset($decoded->data->user->id)) {
                    JSON::sendError(['message' => 'User id not found in token'], 403);
                }

                return $next;
            }
        }

        JSON::sendError(['message' => 'You must be authenticated'], 401);

        return $next;
    }
}
