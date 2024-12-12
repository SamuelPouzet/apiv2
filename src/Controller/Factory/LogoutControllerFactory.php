<?php

namespace SamuelPouzet\Api\Controller\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Controller\LogoutController;

class LogoutControllerFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): LogoutController
    {
        $cookieService = $container->get('cookie.service');
        $jwtService = $container->get('jwt.service');
        $entityManager = $container->get(EntityManager::class);
        return new LogoutController($cookieService, $jwtService, $entityManager);
    }
}