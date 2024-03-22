<?php

declare(strict_types=1);

namespace App\Http\Api\Controllers;

use App\Core\Http\Response;
use App\Core\Http\Security\Authentication;
use JetBrains\PhpStorm\NoReturn;

class SecurityController
{
    /**
     * Login a user
     *
     * @throws \JsonException
     */
    #[NoReturn]
    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

        $login = $data['login'];
        $password = $data['password'];

        $token = Authentication::authenticate($login, $password);

        if ($token) {
            Response::sendJson(['token' => $token]);
        } else {
            Response::sendJson(['error' => 'Invalid credentials'], 401);
        }
    }
}
