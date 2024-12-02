<?php

namespace SamuelPouzet\Api\Manager\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Manager\AuthTokenManager;
use SamuelPouzet\Api\Manager\RefreshTokenManager;

class RefreshTokenManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RefreshTokenManager
    {
        $entityManager = $container->get(EntityManager::class);
        return new RefreshTokenManager($entityManager);
    }
}