<?php

declare(strict_types=1);

namespace App\Core;

use DI\NotFoundException;
use DI\DependencyException;
use DI\Container as DIContainer;

class Container
{
    /**
     * @var DIContainer The dependency injection container instance.
     */
    private static DIContainer $container;

    /**
     * Sets the dependency injection container instance.
     *
     * @param DIContainer $container The dependency injection container instance.
     */
    public static function setContainer(DIContainer $container): void
    {
        self::$container = $container;
    }

    /**
     * Retrieves an instance of the specified class from the dependency injection container.
     *
     * @param string $class The class name for which the instance is requested.
     *
     * @return object The instance of the specified class retrieved from the container.
     */
    public static function getContainer(string $class): object
    {
        try {
            return self::$container->get($class);
        } catch (DependencyException|NotFoundException $e) {
            die($e->getMessage());
        }
    }
}
