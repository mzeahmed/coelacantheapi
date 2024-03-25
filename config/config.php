<?php

use function Env\env;

define('ROOT_PATH', dirname(__DIR__));

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
if (file_exists(ROOT_PATH . '/.env')) {
    $envFiles = file_exists(ROOT_PATH . '/.env.local')
        ? ['.env', '.env.local']
        : ['.env'];

    $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(ROOT_PATH, $envFiles, false);

    $dotenv->load();

    if (!env('DATABASE_URL')) {
        $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST', 'DB_PORT']);
    }
}

define('DB_NAME', env('DB_NAME'));
define('DB_USER', env('DB_USER'));
define('DB_PASSWORD', env('DB_PASSWORD'));
define('DB_HOST', env('DB_HOST'));
define('DB_PORT', env('DB_PORT'));

define('JWT_AUTH_SECRET_KEY', env('JWT_AUTH_SECRET_KEY'));

define('API_URL', env('API_URL'));
define('API_BASE_SLUG', '/api/v1');

require_once ROOT_PATH . '/routes/api.php';
