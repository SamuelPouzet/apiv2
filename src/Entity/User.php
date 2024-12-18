<?php

namespace SamuelPouzet\Api\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use SamuelPouzet\Api\Interface\UserInterface;

#[ORM\Entity(readOnly: false)]
#[ORM\Table(name: 'user')]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    protected int $id;

    #[ORM\Column(name: 'login')]
    protected string $login;

    #[ORM\Column(name: 'password')]
    protected string $password;

    #[ORM\Column(name: 'mail')]
    protected string $mail;

    #[ORM\Column(name: 'date_created')]
    protected \DateTimeImmutable $date_created;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: "users")]
    #[ORM\JoinTable(name: "user_role")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "role_id", referencedColumnName: "id")]
    private PersistentCollection $roles;

    public function __construct()
    {
        $this->roles = new PersistentCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;
        return $this;
    }

    public function getRoles(): PersistentCollection
    {
        return $this->roles;
    }

    public function setRoles(PersistentCollection $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function addRole(Role $role): User
    {
        $this->roles[] = $role;
        return $this;
    }

    public function initRoles()
    {
        $this->roles = new PersistentCollection();
    }

    public function getDateCreated(): \DateTimeImmutable
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeImmutable $date_created): User
    {
        $this->date_created = $date_created;
        return $this;
    }

}