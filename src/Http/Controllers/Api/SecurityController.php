<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helpers\JSON;
use App\Core\Http\Message\Request;
use App\Core\Security\Authentication;
use App\Core\Security\PasswordHasher;
use App\Core\Abstracts\AbstractController;

class SecurityController extends AbstractController
{
    private PasswordHasher $passwordHasher;

    public function __construct(PasswordHasher $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function login(Request $request): void
    {
        $post = $this->getRequestData($request);

        $login = $post['login'];
        $password = $post['password'];

        $data = Authentication::authenticate($login, $password, $this->getEntityManager(), $this->passwordHasher);

        if (!$data) {
            JSON::sendError(['message' => 'Invalid credentials'], 401);
        }

        JSON::sendSuccess(['json' => $data]);
    }

    public function logout(Request $request): void
    {
        $headers = $request->getHeaders();

        if (empty($headers)) {
            JSON::sendError(['message' => 'The headers are empty'], 500);
        }

        $bearer = $headers['Authorization'] ?? '';

        if ($bearer) {
            JSON::sendSuccess(['message' => 'User successfully logged out', 'token' => '']);
        }

        JSON::sendError(['message' => 'You are not authenticated'], 401);
    }
}
