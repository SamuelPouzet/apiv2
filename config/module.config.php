<?php

namespace SamuelPouzet\Api;

use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;
use SamuelPouzet\Api\Controller\AuthController;
use SamuelPouzet\Api\Controller\Factory\AuthControllerFactory;
use SamuelPouzet\Api\Listener\ApiListener;
use SamuelPouzet\Api\Listener\Factory\ApiListenerFactory;
use SamuelPouzet\Api\Plugin\PostFromHeaderPlugin;
use SamuelPouzet\Api\Service\AuthorisationService;
use SamuelPouzet\Api\Service\Factory\AuthorisationServiceFactory;
use Application\Controller\IndexController;

return [
    'router' => [
        'routes' => [
            'auth' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/auth',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'index',
                    ],
                ],
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            AuthController::class => AuthControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            PostFromHeaderPlugin::class => InvokableFactory::class,
        ],
        'aliases' => [
            'postdata.plugin' => PostFromHeaderPlugin::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ApiListener::class => ApiListenerFactory::class,
            AuthorisationService::class => AuthorisationServiceFactory::class,
        ],
        'aliases' => [
            'auth.service' => AuthorisationService::class,
        ],
    ],
    'listeners' => [
        ApiListener::class,
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'authorization' => [
        'allowedByDefault' => false,
        'controllers' => [
            IndexController::class => [
                'getAll' => [
                    'allowed' => false,
                    'roles' => ['role.admin'],
                ],
            ],
        ],
    ],
];