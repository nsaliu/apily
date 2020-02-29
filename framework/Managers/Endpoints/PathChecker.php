<?php

namespace Nazca\Managers\Endpoints;

use DI\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

final class PathChecker
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function isCurrentRoute(Route $route): bool
    {
        /** @var Request $request */
        $request = $this->container->get('request');

        return $request->getPathInfo() === $route->getPath();
    }
}
