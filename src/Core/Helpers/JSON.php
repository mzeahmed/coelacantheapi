<?php

declare(strict_types=1);

namespace App\Core\Helpers;

class JSON
{
    /**
     * Send a JSON response.
     *
     * @param mixed $response Response to send
     * @param int|null $status HTTP status code
     * @param int $options JSON encoding options
     * @return void
     */
    public static function send(mixed $response, int $status = null, int $options = 0): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');

            if (null !== $status) {
                http_response_code($status);
            }
        }

        echo json_encode($response, $options);
        exit();
    }

    /**
     * Send a JSON response, indicating success.
     *
     * @param mixed|null $data Data to send
     * @param int $status HTTP status code
     * @param int $options
     * @return void
     */
    public static function sendSuccess(mixed $data = null, int $status = 200, int $options = 0): void
    {
        $response = ['success' => true];

        if (isset($data)) {
            $response['data'] = $data;
        }

        self::send($response, $status, $options);
    }

    /**
     * Send a JSON response, indicating an error.
     *
     * @param mixed|null $data Data to send
     * @param int $status HTTP status code
     * @param int $options
     * @return void
     */
    public static function sendError(mixed $data = null, int $status = 400, int $options = 0): void
    {
        $response = ['success' => false];

        if (isset($data)) {
            $response['error'] = $data;
        }

        self::send($response, $status, $options);
    }
}
