<?php

namespace SamuelPouzet\Api;

use Doctrine\DBAL\Driver\PDO\MySQL\Driver;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;
use SamuelPouzet\Api\Controller\AuthController;
use SamuelPouzet\Api\Controller\ErrorController;
use SamuelPouzet\Api\Controller\Factory\AuthControllerFactory;
use SamuelPouzet\Api\Controller\TestController;
use SamuelPouzet\Api\Entity\User;
use SamuelPouzet\Api\Interface\UserInterface;
use SamuelPouzet\Api\Listener\ApiListener;
use SamuelPouzet\Api\Listener\Factory\ApiListenerFactory;
use SamuelPouzet\Api\Manager\AuthTokenManager;
use SamuelPouzet\Api\Manager\Factory\AuthTokenManagerFactory;
use SamuelPouzet\Api\Manager\Factory\RefreshTokenManagerFactory;
use SamuelPouzet\Api\Manager\RefreshTokenManager;
use SamuelPouzet\Api\Plugin\PostFromHeaderPlugin;
use SamuelPouzet\Api\Service\AuthenticationService;
use SamuelPouzet\Api\Service\AuthorisationService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\Factory\AuthenticationServiceFactory;
use SamuelPouzet\Api\Service\Factory\AuthorisationServiceFactory;
use Application\Controller\IndexController;
use SamuelPouzet\Api\Service\Factory\JWTServiceFactory;
use SamuelPouzet\Api\Service\Factory\RoleServiceFactory;
use SamuelPouzet\Api\Service\Factory\UserServiceFactory;
use SamuelPouzet\Api\Service\JWTService;
use SamuelPouzet\Api\Service\RoleService;
use SamuelPouzet\Api\Service\UserService;

return [
    'router' => [
        'routes' => [
            'test' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/test',
                    'defaults' => [
                        'controller' => TestController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'auth' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/auth',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            AuthController::class => AuthControllerFactory::class,
            ErrorController::class => InvokableFactory::class,
            TestController::class => InvokableFactory::class,
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
            AuthenticationService::class => AuthenticationServiceFactory::class,
            AuthorisationService::class => AuthorisationServiceFactory::class,
            // todo cookieservicefactory pour récupérer de la conf
            CookieService::class => InvokableFactory::class,
            JWTService::class => JWTServiceFactory::class,
            RoleService::class => RoleServiceFactory::class,
            UserService::class => UserServiceFactory::class,

            AuthTokenManager::class => AuthTokenManagerFactory::class,
            RefreshTokenManager::class => RefreshTokenManagerFactory::class,
        ],
        'aliases' => [
            'authorization.service' => AuthorisationService::class,
            'authentication.service' => AuthenticationService::class,
            'jwt.service' => JWTService::class,
            'cookie.service' => CookieService::class,
            'user.service' => UserService::class,
            'role.service' => RoleService::class,

            'auth.token.manager' => AuthTokenManager::class,
            'refresh.token.manager' => RefreshTokenManager::class,
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
    'jwt' => [
        'payload' => 'mhuiguygyukvykhkjvttgyhjkkknhuukjbb',
        'tokenId' => 'crimson-auth',
        'issuedBy' => 'api.masterforum.sam',
        'permittedFor' => 'masterforum.sam',
        'relatedTo' => 'component-sam',
        
    ],
    'doctrine' => [
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => __DIR__ . '/../data/Migrations',
                'name' => 'Doctrine Database Migrations',
                'namespace' => 'Migrations',
                'table' => 'migrations',
            ],
        ],
        'entity_resolver' => [
            'orm_default' => [
                'resolvers' => [
                    
                ]
            ],
        ],
//        'configuration' => [
//            'orm_default' => [
//                'query_cache'       => 'filesystem',
//                'result_cache'      => 'array',
//                'metadata_cache'    => 'apc',
//                'hydration_cache'   => 'memcached',
//            ],
//        ],
        'connection' => [
            'orm_default' => [
                'driverClass' => Driver::class,
                'params' => [
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'root',
                    'password' => '0000',
                    'dbname' => 'api2025',
                    'driverOptions' => [
                        1002 => 'SET NAMES utf8',
                    ],
                ],
            ],
        ],
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AttributeDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
    ],
    'authorization' => [
        'allowedByDefault' => false,
        'controllers' => [
            AuthController::class => [
                'post' => [
                    'public' => true,
                ],
            ],
            IndexController::class => [
                'getAll' => [
                    'public' => true,
                    'roles' => ['role.admin'],
                ],
            ],
            ErrorController::class => [
                'error' => [
                    'public' => true,
                ],
            ],
            TestController::class => [
                'getAll' => [
                    'public' => false,
                    'roles' => ['role.modo'],
                    'permissions' => ['']
                ],
            ]
        ],
    ],
];