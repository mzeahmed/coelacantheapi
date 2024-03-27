<?php

declare(strict_types=1);

namespace App\Http\Api\Controllers;

use App\Core\Helpers\JSON;
use App\Core\Http\Message\Request;
use App\Core\Http\Security\Authentication;

class SecurityController
{
    public function login(Request $request): void
    {
        $body = $request->getBody();
        $contents = $body->getContents();
        $post = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        $login = $post['login'];
        $password = $post['password'];

        $data = Authentication::authenticate($login, $password);

        if (!$data) {
            JSON::sendError(['message' => 'Invalid credentials'], 401);
        }

        JSON::sendSuccess(['json' => $data]);
    }

    public function logout(Request $request): void
    {
        $headers = $request->getHeaders();

        if (empty($headers)) {
            JSON::sendError(['message' => 'The headers are empty'], 500);
        }

        $bearer = $headers['Authorization'] ?? '';

        if ($bearer) {
            JSON::sendSuccess(['message' => 'User successfully logged out', 'token' => '']);
        }

        JSON::sendError(['message' => 'You are not authenticated'], 401);
    }
}
