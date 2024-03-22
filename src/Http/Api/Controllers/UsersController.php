<?php

declare(strict_types=1);

namespace App\Http\Api\Controllers;

use App\Core\Http\Response;
use App\Services\UserService;

class UsersController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show(int $id): void
    {
        $user = $this->userService->getUser($id);

        if (false === $user) {
            Response::sendJson(['error' => 'User not found'], 404);
        }


        $data = [
            'id' => $user['id'],
            'login' => $user['login'],
            'email' => $user['email'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at'],
            'last_login' => $user['last_login'],
        ];

        Response::sendJson($data);
    }
}
