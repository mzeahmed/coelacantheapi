<?php

declare(strict_types=1);

namespace App\Repository;

// class UsersRepository extends AbstractRepository
// {
//     public function __construct(Database $database)
//     {
//         parent::__construct($database);
//
//         $this->tableName = 'users';
//     }
// }

use Doctrine\ORM\EntityRepository;

class UsersRepository extends EntityRepository
{
}
