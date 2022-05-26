<?php

namespace App\Entity;

use App\Repository\MemoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=MemoryRepository::class)
 * @UniqueEntity("memoryCapacity")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = "expr('/bile-mo-api/v1/memories/' ~ object.getId())"
 * )
 * @Hateoas\Relation(
 *     "create",
 *     href = "expr('/bile-mo-api/v1/memories')"
 * )
 * @Hateoas\Relation(
 *     "list",
 *     href = "expr('/bile-mo-api/v1/memories')"
 * )
 * @Hateoas\Relation(
 *     "update",
 *     href = "expr('/bile-mo-api/v1/memories/' ~ object.getId())"
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href = "expr('/bile-mo-api/v1/memories/' ~ object.getId())"
 * )
 */
class Memory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"show_product"})
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     *
     * @Serializer\Groups({"show_product"})
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
