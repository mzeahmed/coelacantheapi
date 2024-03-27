<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Repository\UsersRepository;

class UserService
{
    private UsersRepository $usersRepo;

    public function __construct(UsersRepository $usersRepo)
    {
        $this->usersRepo = $usersRepo;
    }

    public function getUser(int $id): ?User
    {
        return $this->usersRepo->findOneBy(['id' => $id]);
    }
}
