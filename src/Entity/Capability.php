<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

#[Entity]
#[Table(name: 'capabilities')]
class Capability
{
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(type: Types::INTEGER)]
    private int $id;

    #[Column(type: Types::STRING, nullable: false)]
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
