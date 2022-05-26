<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 * @UniqueEntity("name")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = "expr('/bile-mo-api/v1/countries/' ~ object.getId())"
 * )
 * @Hateoas\Relation(
 *     "create",
 *     href = "expr('/bile-mo-api/v1/countries')"
 * )
 * @Hateoas\Relation(
 *     "list",
 *     href = "expr('/bile-mo-api/v1/countries')"
 * )
 * @Hateoas\Relation(
 *     "update",
 *     href = "expr('/bile-mo-api/v1/countries/' ~ object.getId())"
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href = "expr('/bile-mo-api/v1/countries/' ~ object.getId())"
 * )
 */
class Country
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
     * @ORM\Column(type="string", length=60, unique=true)
     *
     * @Serializer\Groups({"show_product"})
     */
    private string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
