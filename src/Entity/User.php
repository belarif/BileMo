<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = "expr('/bile-mo-api/v1/admins/' ~ object.getId())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(null !== object.getCustomer())")
 * )
 * @Hateoas\Relation(
 *     "create",
 *     href = "expr('/bile-mo-api/v1/admins')",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(null !== object.getCustomer())")
 * )
 * @Hateoas\Relation(
 *     "list",
 *     href = "expr('/bile-mo-api/v1/admins')",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(null !== object.getCustomer())")
 * )
 * @Hateoas\Relation(
 *     "update",
 *     href = "expr('/bile-mo-api/v1/admins/' ~ object.getId())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(null !== object.getCustomer())")
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href = "expr('/bile-mo-api/v1/admins/' ~ object.getId())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(null !== object.getCustomer())")
 * )
 * @Hateoas\Relation(
 *     "self",
 *     href = "expr('/bile-mo-api/v1/visitors/' ~ object.getId())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(null === object.getCustomer())")
 * )
 * @Hateoas\Relation(
 *     "create",
 *     href = "expr('/bile-mo-api/v1/visitors')",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(null === object.getCustomer())")
 * )
 * @Hateoas\Relation(
 *     "list",
 *     href = "expr('/bile-mo-api/v1/visitors')",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(null === object.getCustomer())")
 * )
 * @Hateoas\Relation(
 *     "update",
 *     href = "expr('/bile-mo-api/v1/visitors/' ~ object.getId())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(null === object.getCustomer())")
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href = "expr('/bile-mo-api/v1/visitors/' ~ object.getId())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(null === object.getCustomer())")
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"show_visitor"})
     * @Serializer\Groups({"show_admin"})
     * @Serializer\Groups({"show_customer"})
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     *
     * @Serializer\Groups({"show_visitor"})
     * @Serializer\Groups({"show_admin"})
     * @Serializer\Groups({"show_customer"})
     */
    private string $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"show_visitor"})
     * @Serializer\Groups({"show_admin"})
     */
    private string $password;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class)
     *
     * @Serializer\Groups({"show_visitor"})
     * @Serializer\Groups({"show_admin"})
     * @Serializer\Groups({"show_customer"})
     */
    private $roles;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users")
     *
     * @Serializer\Groups({"show_visitor"})
     * @Serializer\Groups({"show_customer"})
     */
    private $customer;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles->map(function (Role $role) {
            return $role->getRoleName();
        });

        return array_unique($roles->toArray());
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRole(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
