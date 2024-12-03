<?php

namespace SamuelPouzet\Api\Controller;

use Doctrine\ORM\EntityManager;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Entity\User;
use SamuelPouzet\Api\Manager\AuthTokenManager;
use SamuelPouzet\Api\Manager\RefreshTokenManager;
use SamuelPouzet\Api\Service\AuthenticationService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\JWTService;
use SamuelPouzet\Api\Service\TokenService;
use SamuelPouzet\Api\Service\UserService;

class RefreshController extends AbstractActionController
{
    public function __construct(
        protected EntityManager $entityManager,
        protected TokenService $tokenService,
        protected AuthenticationService $authenticationService,
        protected JWTService $JWTService,
        protected CookieService $cookieService,
        protected UserService $userService,
        protected AuthTokenManager $authTokenManager,
        protected RefreshTokenManager $refreshTokenManager
    ) {
    }

    public function postAction(): JsonModel
    {
        $jwt = $this->cookieService->getCookieContent($this->getRequest(), 'refresh-cookie');
        $message = "Refresh OK";

        try {
            if (! $jwt) {
                throw new \Exception('token not valid');
            }
            $content = $this->JWTService->readJwt($jwt);
            $user = $this
                ->userService
                ->getUserByRefreshToken($content->claims()->get('refresh_token'));

            if (! $user) {
                throw new \Exception('invalid user');
            }

            $this->tokenService->generate($this->response, $user);

        } catch (\Exception $exception) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_403);
            $message = $exception->getMessage();
            die($exception->getMessage());
        }

        return new JsonModel([
            'status' => $this->getResponse()->getStatusCode(),
            'message' => $message
        ]);
    }
}
