<?php

namespace Nazca\Managers\Endpoints;

use Nazca\Exceptions\Manager\Endpoint\ActionClassNotFoundException;
use Nazca\Exceptions\Manager\Route\MissingActionDefinitionInRouteException;
use Nazca\Exceptions\Manager\Route\MissingControllerInRouteDefinitionException;
use Nazca\Exceptions\Validator\HttpMethodNotSupportedException;
use Nazca\Managers\Routes\RouteManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;

final class EndpointInvocator implements EndpointInvocatorInterface
{
    /**
     * @var RouteManager
     */
    private $routeManager;

    /**
     * @var Request
     */
    private $request;

    public function __construct(
        RouteManager $routeManager
    ) {
        $this->routeManager = $routeManager;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @return Response
     * @throws ActionClassNotFoundException
     * @throws MissingControllerInRouteDefinitionException
     * @throws MissingActionDefinitionInRouteException
     * @throws HttpMethodNotSupportedException
     */
    public function invoke(): Response
    {
        /** @var Route $route */
        foreach ($this->routeManager->getRoutes() as $routeName => $route) {
            if (!$this->routeManager->isCurrentPath($this->request->getPathInfo())) {
                continue;
            }

            $this->routeManager->validate();

            $actionClass = $this->routeManager->getAction();

            if (!class_exists($actionClass)) {
                throw new ActionClassNotFoundException($actionClass);
            }

            $actionClassInstance = new $actionClass;

            $actionMethod = $this->routeManager->getMethod();

            if ($actionMethod !== null) {
                return $actionClassInstance->{$actionMethod}();
            }

            return $actionClassInstance();
        }

        $pathInfo = $this->request->getPathInfo();
        throw new \Exception("No route found for path {'$pathInfo'}");
    }
}
