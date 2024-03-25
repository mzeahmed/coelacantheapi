<?php

declare(strict_types=1);

namespace App\Core\Http\Security;

use App\Core\Container;
use App\Core\Helpers\JWT;
use App\Core\Http\Response;
use App\Repository\UsersRepository;

class Authentication
{
    public static function authenticate(string $username, string $password): array|bool
    {
        $repo = Container::getContainer(UsersRepository::class);
        $user = $repo->findOneBy(['login' => $username]);

        if (!$user) {
            Response::sendJson(['error' => 'User not found'], 404);
        }

        if (password_verify($password, $user['password'])) {
            $token = JWT::generateToken($user);

            if ($token) {
                return $token;
            }

            Response::sendJson(['error' => 'Error generating token'], 500);
        }

        Response::sendJson(['error' => 'Invalid credentials'], 401);
    }
}
