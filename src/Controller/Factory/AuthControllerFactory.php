<?php

namespace SamuelPouzet\Api\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Controller\AuthController;
use SamuelPouzet\Api\Service\AuthenticationService;

class AuthControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthController
    {
        return new AuthController(
            $container->get('authentication.service'),
            $container->get('jwt.service'),
            $container->get('cookie.service')
        );
    }
}