<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity("company")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = "expr('/bile-mo-api/v1/customers/' ~ object.getId())"
 * )
 * @Hateoas\Relation(
 *     "create",
 *     href = "expr('/bile-mo-api/v1/customers')"
 * )
 * @Hateoas\Relation(
 *     "list",
 *     href = "expr('/bile-mo-api/v1/customers')"
 * )
 * @Hateoas\Relation(
 *     "update",
 *     href = "expr('/bile-mo-api/v1/customers/' ~ object.getId())"
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href = "expr('/bile-mo-api/v1/customers/' ~ object.getId())"
 * )
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"show_customer"})
     * @Groups({"show_visitor"})
     * @Groups({"show_product"})
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=30)
     *
     * @Groups({"show_customer"})
     * @Groups({"show_visitor"})
     */
    private string $code;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"show_customer"})
     * @Groups({"show_visitor"})
     */
    private ?bool $enabled;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     *
     * @Groups({"show_customer"})
     * @Groups({"show_visitor"})
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="customer", orphanRemoval=true)
     *
     * @Groups({"show_customer"})
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCustomer($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCustomer() === $this) {
                $user->setCustomer(null);
            }
        }

        return $this;
    }
}
