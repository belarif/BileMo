<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MemoryRepository::class)
 */
class Memory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private string $memoryCapacity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMemoryCapacity(): ?string
    {
        return $this->memoryCapacity;
    }

    public function setMemoryCapacity(string $memoryCapacity): self
    {
        $this->memoryCapacity = $memoryCapacity;

        return $this;
    }
}
