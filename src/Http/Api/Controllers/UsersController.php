<?php

declare(strict_types=1);

namespace App\Http\Api\Controllers;

use App\Entity\Users;
use App\Core\Helpers\JSON;
use App\Core\Helpers\Strings;
use App\Services\UsersService;
use App\Core\Http\Message\Request;
use Doctrine\ORM\Exception\ORMException;
use App\Core\Abstracts\AbstractController;

class UsersController extends AbstractController
{
    private UsersService $usersService;

    public function __construct(UsersService $userService)
    {
        $this->usersService = $userService;
    }

    public function index(Request $request): void
    {
        $users = $this->usersService->getUsers();

        if (!$users) {
            JSON::sendError(['message' => 'No users found'], 404);
        }

        $data = [];

        foreach ($users as $user) {
            $data[] = $user->userData();
        }

        JSON::sendSuccess($data);
    }

    public function show(Request $request): void
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        $id = Strings::extractIdFromUrl('user', $path);

        $user = $this->usersService->getUser((int) $id);

        if (!$user) {
            JSON::sendError(['message' => 'User not found'], 404);
        }

        JSON::sendSuccess($user->userData());
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

        $user = $this->usersService->createUser($login, $email, $password, $this->getEntityManager());

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

        $this->usersService->updateUser($id, $data, $this->getEntityManager());

        JSON::sendSuccess(['message' => 'User updated successfully']);
    }

    public function delete(Request $request): void
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        $id = Strings::extractIdFromUrl('delete', $path, false);

        $user = $this->usersService->getUser((int) $id);

        if (!$user) {
            JSON::sendError(['message' => 'User not found'], 404);
        }

        try {
            $this->getEntityManager()->remove($user);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            JSON::sendError(['message' => 'Error deleting user => ' . $e->getMessage()], 500);
        }

        JSON::sendSuccess(['message' => 'User deleted successfully']);
    }
}
