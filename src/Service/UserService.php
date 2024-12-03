<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use SamuelPouzet\Api\Entity\AuthToken;
use SamuelPouzet\Api\Entity\RefreshToken;
use SamuelPouzet\Api\Entity\User;

class UserService
{

    public ?User $currentUser;

    public function __construct(protected EntityManager $entityManager)
    {
    }

    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }

    public function setCurrentUser(?User $currentUser): UserService
    {
        $this->currentUser = $currentUser;
        return $this;
    }


    // todo mettre dans un repository
    public function getUserByAccssToken(string $token): ?User
    {
        $now = (new \DateTime())->sub(new \DateInterval('PT2H'));

        $token = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('a, u')
            ->from(AuthToken::class, 'a')
            ->join('a.user', 'u')
            ->where('a.authToken = :token')
            ->andWhere('a.creationDate >= :creation_date')
            ->setParameter('token', $token)
            ->setParameter('creation_date', $now)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $token?->getUser() ?? null;
    }

    // todo mettre dans un repository
    public function getUserByRefreshToken(string $token): ?User
    {
        $now = (new \DateTime())->sub(new \DateInterval('P6M'));

        $token = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('a, u')
            ->from(RefreshToken::class, 'a')
            ->join('a.user', 'u')
            ->where('a.refreshToken = :token')
            ->andWhere('a.creationDate >= :creation_date')
            ->setParameter('token', $token)
            ->setParameter('creation_date', $now)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $token?->getUser() ?? null;
    }
}
