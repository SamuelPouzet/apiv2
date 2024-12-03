<?php

namespace SamuelPouzet\Api\Controller\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Controller\RefreshController;

class RefreshControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RefreshController
    {
        return new RefreshController(
            $container->get(EntityManager::class),
            $container->get('token.service'),
            $container->get('authentication.service'),
            $container->get('jwt.service'),
            $container->get('cookie.service'),
            $container->get('user.service'),
            $container->get('auth.token.manager'),
            $container->get('refresh.token.manager')
        );
    }
}