<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\TokenService;

class TokenServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TokenService
    {
        return  new TokenService(
            $container->get('auth.token.manager'),
            $container->get('refresh.token.manager'),
            $container->get('jwt.service'),
            $container->get('cookie.service')
        );
    }

}