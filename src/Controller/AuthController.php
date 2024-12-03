<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Manager\AuthTokenManager;
use SamuelPouzet\Api\Manager\RefreshTokenManager;
use SamuelPouzet\Api\Service\AuthenticationService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\JWTService;
use SamuelPouzet\Api\Service\TokenService;
use SamuelPouzet\Api\Service\UserService;

// todo override with abstract jsons controller
class AuthController extends AbstractActionController
{
    public function __construct(
        protected TokenService $tokenService,
        protected AuthenticationService $authenticationService,
        protected UserService $userService,
    ) {
    }

    public function postAction(): JsonModel
    {
        $posted = $this->plugin('postdata.plugin')();

        // todo check si les infos sont correctes dans le json
        $granted = $this->authenticationService->authenticate($posted['login'], $posted['password']);
        if ($granted->getStatusCode() === Result::RESULT_KO) {
            return new JsonModel([
                'status' => 0,
                'user' => null,
            ]);
        }

        $user = $this->userService->getCurrentUser();
        $this->tokenService->generate($this->response, $user);

        return new JsonModel([
            'login' => $user->getLogin(),
            'granted' => $granted->getMessage(),
        ]);
    }

}
