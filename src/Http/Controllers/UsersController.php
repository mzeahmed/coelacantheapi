<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Http\Response;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers
 */
class UsersController {
    public function show(int $id): void {
        $data = [
            'id' => $id,
            'name' => 'John Doe',
            'email' => '',
            'age' => 30,
        ];

        Response::sendJson($data);
    }
}
