<?php

declare(strict_types=1);

namespace Nazca\Managers\Endpoints;

use Nazca\Exceptions\Manager\Endpoint\ActionClassNotFoundException;
use Nazca\Exceptions\Manager\Endpoint\ActionMethodNotFoundException;
use Nazca\Exceptions\Manager\Endpoint\RouteNotFoundException;
use Nazca\Exceptions\Manager\Route\MissingActionDefinitionInRouteException;
use Nazca\Exceptions\Manager\Route\MissingControllerInRouteDefinitionException;
use Nazca\Exceptions\Validator\HttpMethodNotSupportedException;
use Nazca\Managers\Routes\RouteManager;
use Nazca\Managers\Routes\RouteManagerInterface;
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
        RouteManagerInterface $routeManager
    ) {
        $this->request = $request;
        $this->routeManager = $routeManager;
    }

    /**
     * @throws ActionClassNotFoundException
     * @throws ActionMethodNotFoundException
     * @throws MissingControllerInRouteDefinitionException
     * @throws MissingActionDefinitionInRouteException
     * @throws HttpMethodNotSupportedException
     * @throws RouteNotFoundException
     */
    public function invoke(): ResponseInterface
    {
        /** @var Route $route */
        foreach ($this->routeManager->getRoutes() as $routeName => $route) {
            if (!$this->routeManager->isCurrentPath($this->request->getUri()->getPath())) {
                continue;
            }

            $this->routeManager->validate();

            $actionClass = $this->routeManager->getActionClass();

            if (!class_exists($actionClass)) {
                throw new ActionClassNotFoundException($actionClass);
            }

            $actionClassInstance = new $actionClass();

            $actionMethod = $this->routeManager->getActionMethod();

            if (null !== $actionMethod) {
                if (!method_exists($actionClassInstance, $actionMethod)) {
                    throw new ActionMethodNotFoundException($actionMethod, $actionClass);
                }

                return $actionClassInstance->{$actionMethod}();
            }

            return $actionClassInstance();
        }

        throw new RouteNotFoundException($this->request->getUri()->getPath());
    }
}
