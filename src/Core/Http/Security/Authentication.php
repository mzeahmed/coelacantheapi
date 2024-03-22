<?php

declare(strict_types=1);

namespace App\Core\Http\Security;

use App\Core\Container;
use App\Repository\UsersRepository;

class Authentication
{
    public static function authenticate(string $username, string $password): string|false
    {
        $repo = Container::getContainer(UsersRepository::class);
        $user = $repo->findOneBy(['login' => $username]);

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user['password'])) {
            return self::generateToken($user);
        }

        return false;
    }

    /**
     * @todo Improve this method to generate a more secure token
     */
    private static function generateToken(array $user): string
    {
        return base64_encode(json_encode(['user_id' => $user['id'], 'exp' => time() + 3600], JSON_THROW_ON_ERROR));
    }
}
