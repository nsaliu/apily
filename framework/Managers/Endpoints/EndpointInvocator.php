<?php

namespace App\Managers\Endpoints;

use App\Managers\Routes\RouteManager;
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
    public function invoke(): Response
    {
        $routeIterator = $this->routeManager->getRoutes()->getIterator();

        /** @var Route $route */
        foreach ($routeIterator as $routeName => $route) {
            if ($this->request->getPathInfo() !== $route->getPath()) {
                continue;
            }

            if (strpos($route->getDefault('_controller'), '::') === false) {
                throw new \Exception("Invalid Configuration for route {$routeName}");
            }

            $tmp = explode('::', $route->getDefault('_controller'));

            $className = $tmp[0];

            if (!class_exists('App\\'.$className)) {
                throw new \Exception("Endopoint class not found for {$className}");
            }

            $method = $tmp[1];

            $fqnClassName = 'App\\'.$className;

            $classInstance = new $fqnClassName;

            if (!method_exists($classInstance, $method)) {
                throw new \Exception("Endopoint method not found for {$className}->{$method}()");
            }

            return $classInstance->{$method}();
        }

        $pathInfo = $this->request->getPathInfo();
        throw new \Exception("No route found for path {'$pathInfo'}");
    }
}
