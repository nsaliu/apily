<?php

namespace Nazca\Managers\Routes;

use Nazca\Exceptions\Manager\Route\MissingActionDefinitionInRouteException;
use Nazca\Exceptions\Manager\Route\MissingControllerInRouteDefinitionException;
use Nazca\Exceptions\Validator\HttpMethodNotSupportedException;
use Nazca\Validators\RouteValidator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class RouteManager
{
    private const ROUTE_FILE_DIR = '/../../../app_config/';

    private const ROUTE_FILE_NAME = 'routes.yaml';

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @var \Iterator
     */
    private $routeCollectionIterator;

    /**
     * @var RouteValidator
     */
    private $routeValidator;

    public function __construct(
        RouteValidator $routeValidator
    ) {
        $this->loadRoutes();
        $this->routeValidator = $routeValidator;
    }

    public function getRoutes(): \Iterator
    {
        return $this->routeCollectionIterator;
    }

    private function loadRoutes(): void
    {
        $fileLocator = new FileLocator([__DIR__ . SELF::ROUTE_FILE_DIR]);
        $loader = new YamlFileLoader($fileLocator);
        $this->routeCollection = $loader->load(SELF::ROUTE_FILE_NAME);
        $this->routeCollectionIterator = $this->routeCollection->getIterator();
    }

    /**
     * @throws HttpMethodNotSupportedException
     */
    public function validate(): void
    {
        $this->routeValidator->validate(
            $this->routeCollectionIterator->current()
        );
    }

    public function isCurrentPath(string $currentPath): bool
    {
        /** @var Route $currentRoute */
        $currentRoute = $this->routeCollectionIterator->current();

        return $currentPath === $currentRoute->getPath();
    }

    /**
     * @throws MissingControllerInRouteDefinitionException
     * @throws MissingActionDefinitionInRouteException
     */
    public function getAction(): ?string
    {
        return $this->extractActionOrMethodName('action');
    }

    /**
     * @throws MissingControllerInRouteDefinitionException
     * @throws MissingActionDefinitionInRouteException
     */
    public function getMethod(): ?string
    {
        return $this->extractActionOrMethodName('method');
    }

    /**
     * @throws MissingControllerInRouteDefinitionException
     * @throws MissingActionDefinitionInRouteException
     */
    private function extractActionOrMethodName(string $subject): ?string
    {
        $route = $this->routeCollectionIterator->current();

        if ($route->getDefault('_controller') === null) {
            throw new MissingControllerInRouteDefinitionException(
                $this->routeCollectionIterator->key()
            );
        }

        if ($subject === 'action') {
            return $this->extractAction($route);
        }

        if ($subject === 'method') {
            return $this->extractMethod($route);
        }

        return null;
    }

    /**
     * @throws MissingActionDefinitionInRouteException
     */
    private function extractAction(Route $route): string
    {
        if (strpos($route->getDefault('_controller'), '::') === false) {
            return 'App\Application\Api\\' . $route->getDefault('_controller');
        }

        $actionInfo = explode('::', $route->getDefault('_controller'));

        if (mb_strlen($actionInfo[0]) === 0) {
            throw new MissingActionDefinitionInRouteException(
                $this->routeCollectionIterator->key()
            );
        }

        return 'App\Application\Api\\' . $actionInfo[0];
    }

    private function extractMethod(Route $route): ?string
    {
        if (strpos($route->getDefault('_controller'), '::') === false) {
            return null;
        }

        $actionInfo = explode('::', $route->getDefault('_controller'));

        if (mb_strlen($actionInfo[1]) === 0) {
            return null;
        }

        return $actionInfo[1];
    }
}
