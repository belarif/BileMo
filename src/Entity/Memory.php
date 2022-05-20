<?php

namespace App\Entity;

use App\Repository\MemoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MemoryRepository::class)
 * @UniqueEntity("memoryCapacity")
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
     * @ORM\Column(type="string", length=10, unique=true)
     *
     * @Groups({"show_product"})
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
