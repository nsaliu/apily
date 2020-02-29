<?php

namespace Nazca\Managers\Endpoints;

use Nazca\Exceptions\Manager\Endpoint\ActionClassNotFoundException;
use Nazca\Exceptions\Manager\Route\MissingActionDefinitionInRouteException;
use Nazca\Exceptions\Manager\Route\MissingControllerInRouteDefinitionException;
use Nazca\Exceptions\Validator\HttpMethodNotSupportedException;
use Nazca\Managers\Routes\RouteManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Routing\Route;

final class EndpointInvocator implements EndpointInvocatorInterface
{
    /**
     * @var RouteManager
     */
    private $routeManager;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    public function __construct(
        ServerRequestInterface $request,
        RouteManager $routeManager
    ) {
        $this->request = $request;
        $this->routeManager = $routeManager;
    }

    /**
     * @throws ActionClassNotFoundException
     * @throws MissingControllerInRouteDefinitionException
     * @throws MissingActionDefinitionInRouteException
     * @throws HttpMethodNotSupportedException
     */
    public function invoke(): ResponseInterface
    {
        /** @var Route $route */
        foreach ($this->routeManager->getRoutes() as $routeName => $route) {
            if (!$this->routeManager->isCurrentPath($this->request->getUri()->getPath())) {
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

        $pathInfo = $this->request->getUri()->getPath();
        throw new \Exception("No route found for path {'$pathInfo'}");
    }
}
