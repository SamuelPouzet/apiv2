<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Service\AuthenticationService;
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
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_403);
            return new JsonModel([
                'status' => Response::STATUS_CODE_403,
                'granted' => $granted->getMessage(),
            ]);
        }

        $user = $this->userService->getCurrentUser();
        $this->tokenService->generate($this->response, $user);

        return new JsonModel([
            'status' => Response::STATUS_CODE_200,
            'login' => $user->getLogin(),
            'granted' => $granted->getMessage(),
        ]);
    }

}
