<?php

declare(strict_types=1);

namespace App\Core\Security;

use App\Core\Interfaces\PasswordHasherInterface;

class PasswordHasher implements PasswordHasherInterface
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
