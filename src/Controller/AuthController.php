<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Service\AuthenticationService;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\JWTService;

// todo override with abstract jsons controller
class AuthController extends AbstractActionController
{

    public function __construct(
        protected AuthenticationService $authenticationService,
        protected JWTService            $JWTService,
        protected CookieService         $cookieService
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
        //todo refresh token + stoquage en cookie
        $interval = new \DateInterval('PT1H');
        $endDate = (new \DateTimeImmutable())->add($interval);
        $authToken = $this
            ->JWTService
            ->expiresAt($endDate)
            ->addClaim('login', $posted['login'])
            ->generate()
            ->toString();
        $this->authCookie('auth-cookie', $authToken, $endDate);


        $interval = new \DateInterval('P6M');
        $endDate = (new \DateTimeImmutable())->add($interval);
        $refreshToken = $this
            ->JWTService
            ->expiresAt($endDate)
            ->generate()
            ->toString();
        $this->authCookie('refresh-cookie', $refreshToken, $endDate);

        return new JsonModel([
            'login' => $posted['login'],
            'password' => $posted['password'],
            'granted' => $granted->getMessage(),
        ]);
    }

    protected function authCookie(string $name, string $value, \DateTimeImmutable $endDate): void
    {
        $this->cookieService->setName($name);
        $this->cookieService->setValue($value);
        $this->cookieService->setExpirationDate($endDate);
        $this->response->getHeaders()->addHeader($this->cookieService->addCookie());
    }

}