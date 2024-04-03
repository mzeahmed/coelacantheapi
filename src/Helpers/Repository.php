<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Core\Database\Connector\DoctrineConnector;

class Repository
{
    public static function findPaginatedObject(string $entity, int $page, int $limit): array
    {
        $manager = DoctrineConnector::getEntityManager();
        $ropository = $manager->getRepository($entity);

        $query = $ropository->createQueryBuilder('u')
                            ->setFirstResult(($page - 1) * $limit)
                            ->setMaxResults($limit)
                            ->getQuery();

        return $query->getResult();
    }
}
