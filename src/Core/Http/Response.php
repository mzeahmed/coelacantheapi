<?php

namespace App\Core\Http;

class Response {
    /**
     * Sends a JSON response to the client.
     *
     * @param array $data The data to send in the response.
     * @param int $status The HTTP status code to send.
     *
     * @return void
     */
    public static function sendJson(array $data, int $status = 200): void {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit();
    }
}
