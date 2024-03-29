<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[Entity]
#[Table(name: 'users')]
class Users
{
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(type: Types::INTEGER)]
    private int $id;

    #[Column(type: Types::STRING, nullable: false)]
    private string $login;

    #[Column(type: Types::STRING, nullable: false)]
    private string $email;

    #[Column(type: Types::STRING, nullable: false)]
    private string $password;

    #[OneToMany(targetEntity: Usermeta::class, mappedBy: 'user')]
    private Collection $usermetas;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Column(name: 'last_login', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $lastLogin;

    #[Column(name: '2fa_token', type: Types::STRING, nullable: true)]
    private ?string $two_fa_token = null;

    public function __construct()
    {
        $this->usermetas = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function getUsermetas(): Collection
    {
        return $this->usermetas;
    }

    public function addUsermeta(Usermeta $usermeta): self
    {
        if (!$this->usermetas->contains($usermeta)) {
            $this->usermetas[] = $usermeta;
            $usermeta->setUser($this);
        }

        return $this;
    }

    public function removeUsermeta(Usermeta $usermeta): self
    {
        if ($this->usermetas->removeElement($usermeta)
            && $usermeta->getUser() === $this) {
            $usermeta->setUser(null);
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->getMetaValueByKey('firstname');
    }

    public function getLastName(): ?string
    {
        return $this->getMetaValueByKey('lastname');
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getBirthdate(): ?string
    {
        return $this->getMetaValueByKey('birthdate');
    }

    public function getAge(): ?int
    {
        $birthdate = $this->getBirthdate();

        if ($birthdate === null) {
            return null;
        }

        $now = new \DateTimeImmutable();

        return $now->diff(new \DateTime($birthdate))->y;
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeImmutable $lastLogin): ?self
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

    public function userData(): array
    {
        return [
            'id' => $this->getId(),
            'login' => $this->getLogin(),
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'last_login' => $this->getLastLogin(),
            'age' => $this->getAge(),
        ];
    }

    private function getMetaValueByKey(string $key): ?string
    {
        foreach ($this->usermetas as $usermeta) {
            if ($usermeta->getMetaKey() === $key) {
                return $usermeta->getMetaValue();
            }
        }

        return null;
    }
}
