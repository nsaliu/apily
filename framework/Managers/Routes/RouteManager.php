<?php

namespace Nazca\Managers\Routes;

use Nazca\Config\ConfigEnum;
use Nazca\Config\ConfigurationServiceInterface;
use Nazca\Exceptions\Manager\Route\MissingActionDefinitionInRouteException;
use Nazca\Exceptions\Manager\Route\MissingControllerInRouteDefinitionException;
use Nazca\Exceptions\Validator\HttpMethodNotSupportedException;
use Nazca\Validators\RouteValidator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class RouteManager implements RouteManagerInterface
{
    /**
     * @var ConfigurationServiceInterface
     */
    private $configurationService;

    /**
     * @var RouteValidator
     */
    private $routeValidator;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @var \Iterator
     */
    private $routeIterator;

    public function __construct(
        ConfigurationServiceInterface $configurationService,
        RouteValidator $routeValidator
    ) {
        $this->configurationService = $configurationService;
        $this->routeValidator = $routeValidator;
        $this->loadRoutes();
    }

    public function getRoutes(): \Iterator
    {
        return $this->routeIterator;
    }

    /**
     * @throws HttpMethodNotSupportedException
     */
    public function validate(): void
    {
        $this->routeValidator->validate(
            $this->routeIterator->current()
        );
    }

    public function isCurrentPath(string $currentPath): bool
    {
        /** @var Route $currentRoute */
        $currentRoute = $this->routeIterator->current();

        return $currentPath === $currentRoute->getPath();
    }

    /**
     * @throws MissingControllerInRouteDefinitionException
     * @throws MissingActionDefinitionInRouteException
     */
    public function getActionClass(): ?string
    {
        return $this->extractActionOrMethodName('action');
    }

    /**
     * @throws MissingControllerInRouteDefinitionException
     * @throws MissingActionDefinitionInRouteException
     */
    public function getActionMethod(): ?string
    {
        return $this->extractActionOrMethodName('method');
    }

    private function loadRoutes(): void
    {
        $fileLocator = new FileLocator([
            sprintf('%s/../../../%s',
                __DIR__,
                $this->configurationService->get(ConfigEnum::ROUTES_DIRECTORY_PATH)
            )
        ]);

        $loader = new YamlFileLoader($fileLocator);

        $this->routeCollection = $loader->load(
            $this->configurationService->get(ConfigEnum::ROUTES_FILE_NAME)
        );

        $this->routeIterator = $this->routeCollection->getIterator();
    }

    /**
     * @throws MissingControllerInRouteDefinitionException
     * @throws MissingActionDefinitionInRouteException
     */
    private function extractActionOrMethodName(string $subject): ?string
    {
        $route = $this->routeIterator->current();

        if ($route->getDefault('_controller') === null) {
            throw new MissingControllerInRouteDefinitionException(
                $this->routeIterator->key()
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
            return sprintf(
                '%s\%s',
                $this->configurationService->get(ConfigEnum::ACTIONS_NAMESPACE),
                $route->getDefault('_controller')
            );
        }

        $actionInfo = explode('::', $route->getDefault('_controller'));

        if (mb_strlen($actionInfo[0]) === 0) {
            throw new MissingActionDefinitionInRouteException(
                $this->routeIterator->key()
            );
        }

        return sprintf(
            '%s\%s',
            $this->configurationService->get(ConfigEnum::ACTIONS_NAMESPACE),
            $actionInfo[0]
        );
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
