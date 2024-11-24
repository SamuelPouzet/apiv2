<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Service\AuthenticationService;
use SamuelPouzet\Api\Service\JWTService;

// todo override with abstract jsons controller
class AuthController extends AbstractActionController
{

    public function __construct(
        protected AuthenticationService $authenticationService,
        protected JWTService            $JWTService
    )
    {

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

        // TOKEN AUTH
        $jwt = $this
            ->JWTService
            ->expiresAt(new \DateInterval('PT1H'))
            ->addClaim('login', $posted['login'])
            ->generate()
            ->toString();

        return new JsonModel([
            'login' => $posted['login'],
            'password' => $posted['password'],
            'granted' => $granted->getMessage(),
            'jwt' => $jwt
        ]);
    }

}