<?php

declare(strict_types=1);

namespace App\Core\Http;

use App\Core\Router\Router;

class RequestHandler {
    /**
     * Handles the incoming HTTP request.
     *
     * @param Router $router The router to use to handle the request.
     *
     * @return void
     */
    public static function handle(Router $router): void {
        // Get JSON datas from the request
        $data = json_decode(file_get_contents('php://input'), true);

        // If no data is found, return an error
        if (null === $data) {
            Response::sendJson(['error' => 'Invalid JSON'], 400);
        }

        // Ensure the request has a 'url' key
        if (!isset($data['url'])) {
            Response::sendJson(['error' => 'Missing "url" in request'], 400);
        }

        $url = $data['url'];

        $router->handleRequest($url);
    }
}
