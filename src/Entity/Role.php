<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'roles')]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Capability::class)]
    #[ORM\JoinTable(name: 'role_capabilities')]
    private Collection $capabilities;

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

    public function getCapabilities(): Collection
    {
        return $this->capabilities;
    }

    public function addCapability(Capability $capability): self
    {
        if (!$this->capabilities->contains($capability)) {
            $this->capabilities[] = $capability;
        }

        return $this;
    }

    public function removeCapability(Capability $capability): self
    {
        $this->capabilities->removeElement($capability);

        return $this;
    }
}
