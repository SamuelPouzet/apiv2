<?php

namespace SamuelPouzet\Api\Manager;

use Doctrine\ORM\EntityManager;
use SamuelPouzet\Api\Entity\AuthToken;
use SamuelPouzet\Api\Entity\RefreshToken;
use SamuelPouzet\Api\Entity\User;

class RefreshTokenManager
{
    public function __construct(protected EntityManager $entityManager)
    {
    }

    public function insert(User $user, string $token): void
    {
        $entity = new RefreshToken();
        $entity->setUser($user);
        $entity->setRefreshToken($token);
        $entity->setCreationDate(new \DateTimeImmutable());
        $this->entityManager->persist($entity);

        $this->entityManager->flush();
    }
}
