<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Abstracts\AbstractRepository;
use App\Core\Database\Database;

class UsersRepository extends AbstractRepository
{
    public function __construct(Database $database)
    {
        parent::__construct($database);

        $this->tableName = 'users';
    }
}
