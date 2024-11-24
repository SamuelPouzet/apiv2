<?php

namespace SamuelPouzet\Api\Service;

class AuthorisationService
{
    public function __construct(
        protected array $config,
    ) {
    }

    // todo définir le retour par une classe
    public function authorize(string $controller, string $action): bool
    {

        // on n'a pas de configuration de définie, on vérifie l'autorisation par défaut
        if (! isset($this->config['controllers'][$controller][$action])) {
            return $this->config['allowedByDefault'] ?? false;
        }

        $config = $this->config['controllers'][$controller][$action];

        // si c'est ouvert pour tout le monde, nul besoin d'aller plus loin
        if (isset($config['allowed']) && $config['allowed']) {
            return true;
        }

        // désormais, on est dans le domaine de l'utilisateur connecté, si on n'a pas de connexion, c'est mort

        return false;
    }
}
