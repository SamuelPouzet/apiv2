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

// todo override with abstract jsons controller
class AuthController extends AbstractActionController
{
    public function __construct(
        protected AuthenticationService $authenticationService,
        protected JWTService $JWTService,
        protected CookieService $cookieService,
        protected AuthTokenManager $authTokenManager,
        protected RefreshTokenManager $refreshTokenManager
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

        $user = $granted->getUser();

        $accessToken = md5(uniqid() . rand(1000000, 9999999));
        $this->authTokenManager->insert($user, $accessToken);
        $refreshToken = md5(uniqid() . rand(1000000, 9999999));
        $this->refreshTokenManager->insert($user, $refreshToken);

        // TOKEN AUTH
        $interval = new \DateInterval('PT5H');
        $endDate = (new \DateTimeImmutable())
            ->setTimezone(new \DateTimeZone("UTC"))
            ->add($interval);
        $authToken = $this
            ->JWTService
            ->build($endDate, [
                'login' => $posted['login'],
                'access_token' => $accessToken
            ])
            ->toString();
        $this->authCookie('auth-cookie', $authToken, $endDate);


        $interval = new \DateInterval('P6M');
        $endDate = (new \DateTimeImmutable())->add($interval);
        $refreshToken = $this
            ->JWTService
            ->build($endDate, [
                'login' => $posted['login'],
                'refresh_token' => $refreshToken
            ])
            ->toString();
        $this->authCookie('refresh-cookie', $refreshToken, $endDate);

        return new JsonModel([
            'login' => $user->getLogin(),
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
