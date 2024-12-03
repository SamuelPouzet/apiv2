<?php

namespace SamuelPouzet\Api\Service\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\AuthenticationService;

class AuthenticationServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthenticationService
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get('user.service');
        return new AuthenticationService($entityManager, $userService);
    }

}