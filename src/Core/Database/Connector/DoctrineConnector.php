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

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, IS_DEV_MODE);

        try {
            $connection = DriverManager::getConnection(DB_PARAMS, $config);
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return new EntityManager($connection, $config);
    }
}