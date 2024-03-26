<?php

declare(strict_types=1);

namespace App\Http\Api\Controllers;

use App\Core\Helpers\JSON;
use App\Core\Http\Message\Request;
use App\Services\UserService;

class UsersController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show(Request $request): void
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        $id = (int)basename($path);

        $user = $this->userService->getUser($id);

        if (false === $user) {
            JSON::sendError(['message' => 'User not found'], 404);
        }


        $data = [
            'id' => $user['id'],
            'login' => $user['login'],
            'email' => $user['email'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at'],
            'last_login' => $user['last_login'],
        ];

        JSON::sendSuccess($data);
    }
}
