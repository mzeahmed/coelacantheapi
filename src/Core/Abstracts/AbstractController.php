<?php

declare(strict_types=1);

namespace App\Core\Abstracts;

abstract class AbstractController
{
    protected function json(array $data, int $status = 200): void
    {
        self::setCorsHeaders();

        http_response_code($status);

        echo json_encode($data, JSON_THROW_ON_ERROR);
        exit();
    }

    private static function setCorsHeaders(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header(
            "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
        );
    }
}