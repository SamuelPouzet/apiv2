<?php

namespace SamuelPouzet\Api\Interface;

interface UserInterface
{
    public function getId(): int;
    public function setId(int $id): static;
    public function getLogin(): string;
    public function setLogin(string $login): static;
}
