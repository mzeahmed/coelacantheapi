<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

#[Entity]
#[Table(name: 'usermeta')]
class Usermeta
{
    #[Column(type: Types::INTEGER)]
    #[Id]
    private int $id;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'usermetas')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[Column(name: 'meta_key', type: Types::STRING, nullable: false)]
    private ?string $metaKey;

    #[Column(name: 'meta_value', type: Types::STRING, nullable: false)]
    private ?string $metaValue;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(?User $user): ?self
    {
        $this->user = $user;

        return $this;
    }

    public function getMetaKey(): string
    {
        return $this->metaKey;
    }

    public function setMetaKey(string $metaKey): self
    {
        $this->metaKey = $metaKey;

        return $this;
    }

    public function getMetaValue(): string
    {
        return $this->metaValue;
    }

    public function setMetaValue(string $metaValue): self
    {
        $this->metaValue = $metaValue;

        return $this;
    }
}
