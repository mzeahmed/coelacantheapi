<?php

declare(strict_types=1);

namespace App\Core\Http\Security;

use App\Core\Container;
use App\Core\Helpers\JSON;
use App\Core\Helpers\JWT;
use App\Repository\UsersRepository;

class Authentication
{
    public static function authenticate(string $username, string $password): array|bool
    {
        $repo = Container::getContainer(UsersRepository::class);
        $user = $repo->findOneBy(['login' => $username]);

        if (!$user) {
            JSON::sendError(['message' => 'User not found'], 404);
        }

        if (password_verify($password, $user['password'])) {
            $token = JWT::generateToken($user);

            if ($token) {
                return $token;
            }

            JSON::sendError(['message' => 'Error generating token'], 500);
        }

        JSON::sendError(['message' => 'Invalid credentials'], 401);

        return false;
    }
}
