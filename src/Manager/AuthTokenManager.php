<?php

namespace SamuelPouzet\Api\Manager;

use Doctrine\ORM\EntityManager;
use SamuelPouzet\Api\Entity\AuthToken;
use SamuelPouzet\Api\Entity\User;

class AuthTokenManager
{
    public function __construct(protected EntityManager $entityManager)
    {
    }

    public function insert(User $user, string $token): void
    {
        $entity = new AuthToken();
        $entity->setUser($user);
        $entity->setAuthToken($token);
        $entity->setCreationDate(new \DateTimeImmutable());
        $this->entityManager->persist($entity);

        $this->entityManager->flush();
    }
}
