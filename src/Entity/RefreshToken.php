<?php
namespace SamuelPouzet\Api\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: false)]
#[ORM\Table(name: 'refresh_token')]
class RefreshToken
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    protected int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    protected User $user;

    #[ORM\Column(name: 'refresh_token')]
    protected string $refreshToken;

    #[ORM\Column(name: 'creation_date')]
    protected ?\DateTimeImmutable $creationDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): RefreshToken
    {
        $this->id = $id;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): RefreshToken
    {
        $this->user = $user;
        return $this;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): RefreshToken
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeImmutable $creationDate): RefreshToken
    {
        $this->creationDate = $creationDate;
        return $this;
    }

}