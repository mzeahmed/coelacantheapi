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
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        http_response_code($status);
        echo json_encode($data);
        exit();
    }
}
