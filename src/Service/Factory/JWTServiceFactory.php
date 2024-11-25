<?php

namespace SamuelPouzet\Api\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Api\Service\JWTService;

class JWTServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): JWTService
    {
        $config = $this->getConfig($container->get('config'));
        return new JWTService($config);
    }

    protected function getConfig(array $config): array
    {
        return $config['jwt'] ?? [];
    }
}