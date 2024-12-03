<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Http\Response;
use Laminas\Mvc\MvcEvent;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use SamuelPouzet\Api\Adapter\Result;

class AuthorisationService
{
    public function __construct(
        protected array         $config,
        protected CookieService $cookieService,
        protected JWTService    $JWTService,
        protected UserService   $userService,
        protected RoleService   $roleService
    )
    {
    }

    public function authorize(MvcEvent $event): Result
    {

        $result = new Result();

        $routeMatch = $event->getRouteMatch();
        $action = $routeMatch->getParam('action');
        $controller = $routeMatch->getParam('controller');

        // on n'a pas de configuration de définie, on vérifie l'autorisation par défaut
        if (!isset($this->config['controllers'][$controller][$action])) {
            if ($this->config['allowedByDefault']) {
                return $result
                    ->setStatusCode(Response::STATUS_CODE_200)
                    ->setMessage('Config not found and allowed by default');
            }
            return $result
                ->setStatusCode(Response::STATUS_CODE_403)
                ->setMessage('Config not found and disallowed by default');
        }

        $config = $this->config['controllers'][$controller][$action];

        // si c'est ouvert pour tout le monde, nul besoin d'aller plus loin
        if (isset($config['public']) && $config['public']) {
            return $result
                ->setStatusCode(Response::STATUS_CODE_200)
                ->setMessage('Allowed for everyone');
        }

        // désormais, on est dans le domaine de l'utilisateur connecté, si on n'a pas de connexion, c'est mort
        $jwt = $this->cookieService->getCookieContent($event->getRequest(), 'auth-cookie');

        if (! $jwt) {
            return $result
                ->setStatusCode(Response::STATUS_CODE_401)
                ->setMessage('Token expired');
        }

        try {
            $decrypted = $this->JWTService->readJwt($jwt);
        } catch (InvalidTokenStructure $exception) {
            return $result
                ->setStatusCode(Response::STATUS_CODE_403)
                ->setMessage('Token mismatch');
        }

        if ($decrypted) {
            $accessToken = $decrypted->claims()->get('access_token');

            // on récupère le compte utilisateur via le token
            $user = $this->userService->getUserByAccssToken($accessToken);

            // token invalide ou expiré, on ne retrouve pas l'utilisateur
            if (! $user) {
                return $result
                    ->setStatusCode(Response::STATUS_CODE_403)
                    ->setMessage('Bad user');
            }

            if ($config['roles']) {
                if ($this->roleService->isRoleAllowedForUser($user, $config['roles'])) {
                    return $result
                        ->setStatusCode(Response::STATUS_CODE_200)
                        ->setMessage('User allowed by role');
                }
            }

            return $result
                ->setStatusCode(Response::STATUS_CODE_401)
                ->setMessage('User not allowed');
        }

        return $result
            ->setStatusCode(Response::STATUS_CODE_403)
            ->setMessage('Cannot decrypt token');
    }
}
