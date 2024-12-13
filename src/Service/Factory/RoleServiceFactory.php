<?php

namespace SamuelPouzet\Api\Service\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\RoleService;

class RoleServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RoleService
    {
        $entityManager = $container->get(EntityManager::class);
        $cache = $container->get('default-cache');
        return new RoleService($entityManager, $cache);
    }

}