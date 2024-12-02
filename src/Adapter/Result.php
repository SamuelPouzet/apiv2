<?php

namespace SamuelPouzet\Api\Adapter;

use SamuelPouzet\Api\Entity\User;

class Result
{

    protected int $statusCode;
    protected string $message;
    protected ?User $user;

    public const RESULT_OK = 1;
    public const RESULT_KO = 0;

    public function __construct()
    {
        $this
            ->setMessage('Allright')
            ->setStatusCode(Result::RESULT_OK);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): Result
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Result
    {
        $this->message = $message;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Result
    {
        $this->user = $user;
        return $this;
    }
}