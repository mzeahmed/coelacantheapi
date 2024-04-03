<?php

declare(strict_types=1);

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

function cliDbConfig(): EntityManager
{
    $params = DB_PARAMS;
    $params['host'] = '127.0.0.1:' . MARIA_DB_DEV_HOST_PORT;

    $paths = [ROOT_PATH . '/src/Entity'];
    $ORMConfig = ORMSetup::createAttributeMetadataConfiguration($paths, IS_DEV_MODE);
    $connection = DriverManager::getConnection(array_merge($params, ['memory' => true]));

    return new EntityManager($connection, $ORMConfig);
}
