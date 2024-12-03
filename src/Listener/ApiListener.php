<?php

namespace SamuelPouzet\Api\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Http\Response;
use Laminas\Mvc\MvcEvent;
use SamuelPouzet\Api\Adapter\Result;
use SamuelPouzet\Api\Controller\ErrorController;
use SamuelPouzet\Api\Exception\MethodNotFoundException;
use SamuelPouzet\Api\Service\AuthorisationService;

class ApiListener
{
    protected array $listeners;

    public function __construct(
        protected AuthorisationService $authorisationService
    )
    {

    }

    public function attach(EventManagerInterface $events, int $priority = 1): void
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this, 'route'],
            $priority
        );
    }

    public function route(MvcEvent $event): void
    {
        $routeMatch = $event->getRouteMatch();
        $method = $this->getAction($event);
        $routeMatch->setParam('action', $method);
        try {
            $auth = $this->authorize($event);

            if ($auth->getStatusCode() !== Response::STATUS_CODE_200) {
                $routeMatch->setParam('controller', ErrorController::class);
                $routeMatch->setParam('action', 'error');
                $routeMatch->setParam('statusCode', $auth->getStatusCode());
                $routeMatch->setParam('message', $auth->getMessage());
                return;
            }
        } catch (\Exception $e) {
            die('Exception ' . $e->getMessage());
        } catch (\Error $e) {
            die('Erreur ' . $e->getMessage());
        }


    }

    protected function getAction(MvcEvent $event): string
    {
        $request = $event->getApplication()->getRequest();
        $method = strtolower($request->getMethod());

        if (strtolower($method) === 'get') {
            $params = array_diff_key($event->getRouteMatch()->getParams(), ['controller' => 0, 'action' => 0]);
            if (0 === count($params)) {
                $method = 'getAll';
            }
        }

        return $method;
    }

    protected function authorize(MvcEvent $event): Result
    {
        return $this->authorisationService->authorize($event);
    }
}
