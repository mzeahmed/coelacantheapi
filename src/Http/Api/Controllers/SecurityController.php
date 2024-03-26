<?php

declare(strict_types=1);

namespace App\Http\Api\Controllers;

use App\Core\Http\Message\Request;
use App\Core\Http\Security\Authentication;
use App\Core\Abstracts\AbstractController;

class SecurityController extends AbstractController
{
    /**
     * Login a user
     *
     * @throws \JsonException
     */
    public function login(Request $request): void
    {
        $body = $request->getBody();
        $contents = $body->getContents();
        $post = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        $login = $post['login'];
        $password = $post['password'];

        $data = Authentication::authenticate($login, $password);

        if (!$data) {
            $this->json(['error' => 'Invalid credentials'], 401);
        }

        $this->json(['data' => $data]);
    }
}
