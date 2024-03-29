<?php

declare(strict_types=1);

function cliDbConfig(): \Doctrine\ORM\EntityManager
{
    $params = DB_PARAMS;
    $params['host'] = '127.0.0.1:' . MARIA_DB_DEV_HOST_PORT;

    $paths = [ROOT_PATH . '/src/Entity'];
    $ORMConfig = \Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration($paths, IS_DEV_MODE);
    $connection = \Doctrine\DBAL\DriverManager::getConnection(array_merge($params, ['memory' => true]));

    return new \Doctrine\ORM\EntityManager($connection, $ORMConfig);
}
