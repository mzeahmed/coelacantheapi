<?php

declare(strict_types=1);

namespace App\Http\Api\Controllers;

use App\Core\Helpers\JSON;
use App\Services\UserService;
use App\Core\Http\Message\Request;

class UsersController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): void
    {
        $users = $this->userService->getUsers();

        if (!$users) {
            JSON::sendError(['message' => 'No users found'], 404);
        }

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'login' => $user->getLogin(),
                'email' => $user->getEmail(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'created_at' => $user->getCreatedAt(),
                'updated_at' => $user->getUpdatedAt(),
                'last_login' => $user->getLastLogin(),
            ];
        }

        JSON::sendSuccess($data);
    }

    public function show(Request $request): void
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        $id = (int) basename($path);

        $user = $this->userService->getUser($id);

        if (!$user) {
            JSON::sendError(['message' => 'User not found'], 404);
        }

        $data = [
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt(),
            'last_login' => $user->getLastLogin(),
        ];

        JSON::sendSuccess($data);
    }
}
