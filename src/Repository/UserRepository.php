<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findPaginatedUsers(int $page, int $limit): array
    {
        $query = $this->createQueryBuilder('u')
                      ->setFirstResult(($page - 1) * $limit)
                      ->setMaxResults($limit)
                      ->getQuery();

        return $query->getResult();
    }
}
