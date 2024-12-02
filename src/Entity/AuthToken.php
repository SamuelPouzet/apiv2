<?php

namespace SamuelPouzet\Api\Entity;


use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: false)]
#[ORM\Table(name: 'auth_token')]
class AuthToken
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    protected int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    protected User $user;

    #[ORM\Column(name: 'auth_token')]
    protected string $authToken;

    #[ORM\Column(name: 'creation_date')]
    protected ?\DateTimeImmutable $creationDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): AuthToken
    {
        $this->id = $id;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): AuthToken
    {
        $this->user = $user;
        return $this;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function setAuthToken(string $authToken): AuthToken
    {
        $this->authToken = $authToken;
        return $this;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeImmutable $creationDate): AuthToken
    {
        $this->creationDate = $creationDate;
        return $this;
    }

}