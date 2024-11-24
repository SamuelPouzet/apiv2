<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Crypt\Password\Bcrypt;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Entity\User;

class AuthenticationService
{
    public function __construct(protected EntityManager $entityManager)
    {
    }

    public function authenticate(string $login, string $password): Result
    {
        $result = new Result();
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'login' => $login
        ]);

        if (! $user) {
            return $result
                ->setMessage('user inexistant')
                ->setStatusCode(Result::RESULT_KO);
        }

        $bcrypt = new Bcrypt();
        if (! $bcrypt->verify($password, $user->getPassword())) {
            return $result
                ->setMessage('Erreur dans le mot de passe')
                ->setStatusCode(Result::RESULT_KO);
        }

        return $result;
    }

}