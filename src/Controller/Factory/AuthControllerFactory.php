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
        $authenticationService = $container->get('authentication.service');
        return new AuthController($authenticationService);
    }

}