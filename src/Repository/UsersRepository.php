<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Abstracts\AbstractRepository;

class UsersRepository extends AbstractRepository {
    public function __construct() {
        parent::__construct();

        $this->tableName = 'users';
    }
}
