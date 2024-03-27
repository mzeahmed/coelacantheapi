<?php

declare(strict_types=1);

namespace App\Core\Database\Connector;

use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

class DoctrineConnector
{
    public static function getEntityManager(): EntityManager
    {
        $paths = [ROOT_PATH . '/src/Entity'];
        $connection = null;

        $dbParams = [
            'dbname' => DB_NAME,
            'user' => DB_USER,
            'password' => DB_PASSWORD,
            'host' => DB_HOST,
            'port' => DB_PORT,
            'driver' => 'pdo_mysql',
        ];

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, IS_DEV_MODE);

        try {
            $connection = DriverManager::getConnection($dbParams, $config);
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return new EntityManager($connection, $config);
    }
}
