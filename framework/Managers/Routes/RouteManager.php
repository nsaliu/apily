<?php

namespace App\Managers\Routes;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

final class RouteManager
{
    private const ROUTE_FILE_DIR = '/../../../configuration';

    private const ROUTE_FILE_NAME = 'routes.yaml';

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    public function __construct()
    {
        $this->loadRoutes();
    }

    public function getRoutes(): \IteratorAggregate
    {
        return $this->routeCollection;
    }

    private function loadRoutes(): void
    {
        $fileLocator = new FileLocator([__DIR__ . SELF::ROUTE_FILE_DIR]);
        $loader = new YamlFileLoader($fileLocator);
        $this->routeCollection = $loader->load(SELF::ROUTE_FILE_NAME);
    }
}
