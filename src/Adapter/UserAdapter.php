<?php

namespace SamuelPouzet\Api\Adapter;

class UserAdapter
{

    protected string $login;

    protected string $password;

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): UserAdapter
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): UserAdapter
    {
        $this->password = $password;
        return $this;
    }

}