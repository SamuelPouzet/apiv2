<?php

namespace SamuelPouzet\Api\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
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
        $controller = $routeMatch->getParam('controller');
        $routeMatch->setParam('action', $method);

        try {
            $this->authorize($controller, $method);
        } catch (\Exception $e) {
            die('Exception ' . $e->getMessage());
        } catch (\Error $e) {
            die('Erreur ' . $e->getMessage());
        }

        $routeMatch->setParam('action', $method);
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

    protected function authorize(string $controller, string $action)
    {
        $allowed = $this->authorisationService->authorize($controller, $action);
    }
}
