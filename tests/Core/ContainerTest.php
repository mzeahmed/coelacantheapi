<?php

declare(strict_types=1);

use App\Core\Container;
use PHPUnit\Framework\TestCase;
use DI\Container as DIContainer;
use Doctrine\ORM\EntityManagerInterface;

class ContainerTest extends TestCase
{
    public function testGetContainer()
    {
        $container = new DIContainer();
        Container::initializeContainer($container);

        // We test one random class to see if the container is working
        $class = Container::getContainer(EntityManagerInterface::class);
        $this->assertInstanceOf(EntityManagerInterface::class, $class);
    }
}
