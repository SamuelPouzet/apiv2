<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Mvc\MvcEvent;
use Lcobucci\JWT\Token\InvalidTokenStructure;

class AuthorisationService
{
    public function __construct(
        protected array $config,
        protected CookieService $cookieService,
        protected JWTService $JWTService,
        protected UserService $userService,
        protected RoleService $roleService
    ) {
    }

    // todo définir le retour par une classe
    public function authorize(MvcEvent $event): bool
    {

        $routeMatch = $event->getRouteMatch();
        $action = $routeMatch->getParam('action');
        $controller = $routeMatch->getParam('controller');

        // on n'a pas de configuration de définie, on vérifie l'autorisation par défaut
        if (! isset($this->config['controllers'][$controller][$action])) {
            return $this->config['allowedByDefault'] ?? false;
        }

        $config = $this->config['controllers'][$controller][$action];

        // si c'est ouvert pour tout le monde, nul besoin d'aller plus loin
        if (isset($config['public']) && $config['public']) {
            return true;
        }

        // désormais, on est dans le domaine de l'utilisateur connecté, si on n'a pas de connexion, c'est mort
        $jwt = $this->cookieService->getCookieContent($event->getRequest(), 'auth-cookie');

        try {
            $decrypted = $this->JWTService->readJwt($jwt);
        } catch (InvalidTokenStructure $exception) {
            die($exception->getMessage());
            return false;
        }

        if ($decrypted) {
            $accessToken = $decrypted->claims()->get('access_token');

            // on récupère le compte utilisateur via le token
            $user = $this->userService->getUserByAccssToken($accessToken);

            // token invalide ou expiré, on ne retrouve pas l'utilisateur
            if (! $user) {
                return false;
            }

            if($config['roles']) {
                var_dump($this->roleService->isRoleAllowedForUser($user, $config['roles']));
            }

            die($user->getLogin());

            return true;
        }

        return false;
    }
}
