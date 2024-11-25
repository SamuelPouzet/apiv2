<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\AuthorisationService;

class AuthorisationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthorisationService
    {
        $config = $this->getAuthorization($container->get('config'));
        return new AuthorisationService($config);
    }

    protected function getAuthorization(array $config): array
    {
        return $config['authorization'] ?? [];
    }
}