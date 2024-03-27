<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testGetInstance(): void
    {
        $result = App\Bootstrap::getInstance();
        $this->assertInstanceOf(App\Bootstrap::class, $result);
    }

    public function testInit(): void
    {
        $bootstrap = App\Bootstrap::getInstance();

        $config = dirname(__DIR__) . '/config/config.php';
        $this->assertFileExists($config);

        $dotenv = $this->createMock(\Dotenv\Dotenv::class);
        $dotenv->method('load')->willReturn(true);

        $this->assertNull($bootstrap->init());
    }
}
