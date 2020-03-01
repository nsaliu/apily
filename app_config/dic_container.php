<?php

use Laminas\Diactoros\ServerRequest;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nazca\Config\ConfigurationService;
use Nazca\Config\ConfigurationServiceInterface;
use Nazca\Managers\Endpoints\EndpointInvocator;
use Nazca\Managers\Endpoints\EndpointInvocatorInterface;
use Nazca\Managers\Http\HttpRequestManager;
use Nazca\Managers\Http\HttpRequestManagerInterface;
use Nazca\Managers\Routes\RouteManager;
use Nazca\Managers\Routes\RouteManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

return [

    # >>> framework settings

    ContainerInterface::class => DI\Container::class,

    ConfigurationServiceInterface::class => DI\get(ConfigurationService::class),

    ServerRequestInterface::class => DI\get(ServerRequest::class),

    EmitterInterface::class => DI\get(SapiEmitter::class),

    EndpointInvocatorInterface::class => DI\get(EndpointInvocator::class),

    RouteManagerInterface::class => DI\get(RouteManager::class),

    HttpRequestManagerInterface::class => DI\get(HttpRequestManager::class),

    # <<< framework settings

    # >>> custom settings
    # ...
    # <<< custom settings
];
