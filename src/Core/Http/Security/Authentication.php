<?php

declare(strict_types=1);

namespace App\Core\Http\Security;

use App\Repository\UsersRepository;

/**
 * Class Authentication
 *
 * @package App\Core\Http\Security
 */
class Authentication {
    /**
     * Authenticate a user
     *
     * @param string $username
     * @param string $password
     *
     * @return string|false The token if the authentication is successful, false otherwise
     */
    public static function authenticate(string $username, string $password): string|false {
        $repo = new UsersRepository();
        $user = $repo->findOneBy(['login' => $username]);

        // User not found
        if (!$user) {
            return false;
        }

        if (password_verify($password, $user['password'])) {
            return self::generateToken($user);
        }

        // Invalid password
        return false;
    }

    /**
     * Generate authentication token for user
     *
     * @param array $user User data
     *
     * @return string The authentication token
     * @todo Improve this method to generate a more secure token
     */
    private static function generateToken(array $user): string {
        return base64_encode(json_encode(['user_id' => $user['id'], 'exp' => time() + 3600])); // Expires in 1 hour
    }
}
