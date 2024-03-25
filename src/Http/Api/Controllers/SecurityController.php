<?php

declare(strict_types=1);

namespace App\Http\Api\Controllers;

use App\Core\Http\Response;
use App\Core\Http\Security\Authentication;

class SecurityController
{
    /**
     * Login a user
     *
     * @throws \JsonException
     */
    public function login(): void
    {
        $post = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

        $login = $post['login'];
        $password = $post['password'];

        $data = Authentication::authenticate($login, $password);

        if ($data) {
            Response::sendJson(['data' => $data]);
        } else {
            Response::sendJson(['error' => 'Invalid credentials'], 401);
        }
    }
}
