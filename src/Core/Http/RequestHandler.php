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
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uriSegments = explode('/', trim($uri, '/'));

        // Remove empty segments
        $uriSegments = array_filter($uriSegments);

        if (empty($uriSegments)) {
            Response::sendJson(['err', 'Route not found'], 404);
        }

        $url = '/' . implode('/', $uriSegments);

        $router->handleRequest($url);
    }
}
