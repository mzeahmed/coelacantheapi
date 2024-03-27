<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;

#[Entity]
#[Table(name: 'users')]
class User
{
    #[Column(type: Types::INTEGER)]
    #[Id]
    private int $id;

    #[Column(type: Types::STRING, nullable: false)]
    private string $login;

    #[Column(type: Types::STRING, nullable: false)]
    private string $email;

    #[Column(type: Types::STRING, nullable: false)]
    private string $password;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $updatedAt;

    #[Column(name: 'last_login', type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $lastLogin;

    #[Column(name: '2fa_token', type: Types::STRING, nullable: true)]
    private ?string $two_fa_token = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLastLogin(): \DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeImmutable $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getTwoFaToken(): ?string
    {
        return $this->two_fa_token;
    }

    public function setTwoFaToken(?string $two_fa_token): self
    {
        $this->two_fa_token = $two_fa_token;

        return $this;
    }
}
