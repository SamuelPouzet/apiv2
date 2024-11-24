<?php

namespace SamuelPouzet\Api\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: false)]
#[ORM\Table(name: 'user')]
class User
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): User
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): User
    {
        $this->mail = $mail;
        return $this;
    }

}