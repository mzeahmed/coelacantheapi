<?php

declare(strict_types=1);

namespace App\Http\Controllers;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers
 */
class UsersController {
    public function index(): array {
        return array('message' => 'Hello, world!');
    }
}
