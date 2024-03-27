<?php

declare(strict_types=1);

namespace App\Core;

use App\Entity\User;
use DI\ContainerBuilder;
use DI\NotFoundException;
use DI\DependencyException;
use DI\Container as DIContainer;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use App\Core\Database\Connector\DoctrineConnector;

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

    public static function initializeContainer(&$container): void
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAttributes(false);

        $entityManager = DoctrineConnector::getEntityManager();

        $containerBuilder->addDefinitions([
            EntityManagerInterface::class => $entityManager,
            ClassMetadata::class => function () use ($entityManager) {
                return $entityManager->getClassMetadata(User::class);
            },
        ]);

        try {
            $container = $containerBuilder->build();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        self::setContainer($container);
    }
}
