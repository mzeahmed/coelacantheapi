<?php

declare(strict_types=1);

namespace App\Http\Api\Controllers;

use App\Entity\Users;
use App\Core\Helpers\JSON;
use App\Core\Helpers\Strings;
use App\Services\UsersService;
use App\Core\Http\Message\Request;
use App\Core\Abstracts\AbstractController;

class UsersController extends AbstractController
{
    private UsersService $userService;

    public function __construct(UsersService $userService)
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
            $data[] = $user->userData();
        }

        JSON::sendSuccess($data);
    }

    public function create(Request $request): void
    {
        $post = $this->getRequestData($request);

        $login = $post['login'];
        $email = $post['email'];
        $password = $post['password'];

        $loginExists = $this->getEntityManager()->getRepository(Users::class)->findOneBy(['login' => $login]);
        $emailExists = $this->getEntityManager()->getRepository(Users::class)->findOneBy(['email' => $email]);

        if ($loginExists) {
            JSON::sendError(['message' => sprintf('Login %s is already in use', $login)], 500);
        }

        if ($emailExists) {
            JSON::sendError(['message' => sprintf('Email %s is already in use', $email)], 500);
        }

        $user = $this->userService->createUser($login, $email, $password, $this->getEntityManager());

        if (!$user) {
            JSON::sendError(['message' => 'Error creating user'], 500);
        }

        JSON::sendSuccess(['message' => sprintf('User %s created successfully', $login)]);
    }

    public function update(Request $request): void
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        $id = Strings::extractIdFromUrl('update', $path, false);

        $data = $this->getRequestData($request);

        $update = $this->userService->updateUser($id, $data, $this->getEntityManager());

        if (!$update) {
            JSON::sendError(['message' => 'Error updating user'], 500);
        }

        JSON::sendSuccess(['message' => 'User updated successfully']);
    }

    public function show(Request $request): void
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        $id = Strings::extractIdFromUrl('user', $path);

        $user = $this->userService->getUser((int) $id);

        if (!$user) {
            JSON::sendError(['message' => 'User not found'], 404);
        }

        JSON::sendSuccess($user->userData());
    }

}
