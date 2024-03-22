<?php

declare(strict_types=1);

namespace App\Core;

use DI\Container as DIContainer;

class Container
{
    private static DIContainer $container;

    public static function setContainer(DIContainer $container): void
    {
        self::$container = $container;
    }

    public static function getContainer(string $class): object
    {
        return self::$container->get($class);
    }
}
