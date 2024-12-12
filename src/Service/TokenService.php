<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Stdlib\ResponseInterface;
use SamuelPouzet\Api\Entity\User;
use SamuelPouzet\Api\Manager\AuthTokenManager;
use SamuelPouzet\Api\Manager\RefreshTokenManager;

class TokenService
{
    public function __construct(
        protected AuthTokenManager $authTokenManager,
        protected RefreshTokenManager $refreshTokenManager,
        protected JWTService $JWTService,
        protected CookieService $cookieService
    ) {
    }

    public function generate(ResponseInterface $response, User $user)
    {
        $accessToken = md5(uniqid() . rand(1000000, 9999999));
        $refreshToken = md5(uniqid() . rand(1000000, 9999999));

        $this->authTokenManager->insert($user, $accessToken);
        $this->refreshTokenManager->insert($user, $refreshToken);

        // TOKEN AUTH
        $interval = new \DateInterval('PT5H');
        $endDate = (new \DateTimeImmutable())
            ->setTimezone(new \DateTimeZone("UTC"))
            ->add($interval);
        $authToken = $this
            ->JWTService
            ->build($endDate, [
                'login' => $user->getLogin(),
                'access_token' => $accessToken
            ])
            ->toString();
        $this->authCookie($response, 'auth-cookie', $authToken, $endDate);


        $interval = new \DateInterval('P6M');
        $endDate = (new \DateTimeImmutable())->add($interval);
        $refreshToken = $this
            ->JWTService
            ->build($endDate, [
                'login' => $user->getLogin(),
                'refresh_token' => $refreshToken
            ])
            ->toString();
        $this->authCookie($response, 'refresh-cookie', $refreshToken, $endDate);
    }

    protected function authCookie(
        ResponseInterface $response,
        string $name,
        string $value,
        \DateTimeImmutable $endDate
    ): void {
        $this->cookieService->setName($name);
        $this->cookieService->setValue($value);
        $this->cookieService->setExpirationDate($endDate);

        // $response->getHeaders()->removeHeader($this->cookieService->addCookie());
        $response->getHeaders()->addHeader($this->cookieService->addCookie());
    }
}
