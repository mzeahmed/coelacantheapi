<?php

declare(strict_types=1);

namespace App;

class Bootstrap
{
    private static ?self $instance = null;

    public function __construct()
    {
        $this->init();
    }

    final public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function init(): void
    {
        require_once dirname(__DIR__) . '/config/config.php';

        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
    }
}
