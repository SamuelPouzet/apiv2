<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\AuthorisationService;

class AuthorisationServiceFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        ?array $options = null
    ): AuthorisationService {
        $config = $this->getAuthorization($container->get('config'));
        $cookieService = $container->get('cookie.service');
        $jwtService = $container->get('jwt.service');
        $userService = $container->get('user.service');
        $roleService = $container->get('role.service');
        return new AuthorisationService($config, $cookieService, $jwtService, $userService, $roleService);
    }

    protected function getAuthorization(array $config): array
    {
        return $config['authorization'] ?? [];
    }
}
