<?php

namespace SamuelPouzet\Api\Controller;

use Doctrine\ORM\EntityManager;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use SamuelPouzet\Api\Entity\AuthToken;
use SamuelPouzet\Api\Entity\RefreshToken;
use SamuelPouzet\Api\Service\CookieService;
use SamuelPouzet\Api\Service\JWTService;

class LogoutController extends AbstractActionController
{
    public function __construct(
        protected CookieService $cookieService,
        protected JWTService    $JWTService,
        protected EntityManager $entityManager
    )
    {
    }

    public function postAction(): JsonModel
    {
        // todo modifier date expiration en base
        $now = new \DateTimeImmutable();

        try {
            $jwt = $this->cookieService->getCookieContent($this->getRequest(), 'auth-cookie');
            $content = $this->JWTService->readJwt($jwt);
            $tokenValue = $content->claims()->get('access_token');

            $oldToken = $this->entityManager->getRepository(AuthToken::class)->findOneBy(['authToken' => $tokenValue]);
            $this->entityManager->remove($oldToken);

            $jwt = $this->cookieService->getCookieContent($this->getRequest(), 'refresh-cookie');
            $content = $this->JWTService->readJwt($jwt);
            $tokenValue = $content->claims()->get('refresh_token');

            $oldToken = $this->entityManager->getRepository(RefreshToken::class)->findOneBy(['refreshToken' => $tokenValue]);
            $this->entityManager->remove($oldToken);

            $this->entityManager->flush();

            $cookie = $this
                ->cookieService
                ->setExpirationDate($now)
                ->setName('auth-cookie')
                ->setValue('')
                ->addCookie();
            $this->getResponse()->getHeaders()->addHeader($cookie);

            $cookie = $this
                ->cookieService
                ->setExpirationDate($now)
                ->setName('refresh-cookie')
                ->setValue('')
                ->addCookie();

            $this->getResponse()->getHeaders()->addHeader($cookie);
        } catch (\Exception $exception) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_500);
            return new JsonModel([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);

        } catch (\Error $error) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_500);
            return new JsonModel([
                'code' => $error->getCode(),
                'message' => $error->getMessage(),
            ]);
        }


        return new JsonModel([]);
    }
}
