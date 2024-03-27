<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Repository\UsersRepository;

class UserService
{
    private UsersRepository $repo;

    public function __construct(UsersRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getUser(int $id): ?User
    {
        return $this->repo->findOneBy(['id' => $id]);
    }
}
