<?php

use Laminas\Diactoros\ServerRequest;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nazca\Managers\Endpoints\EndpointInvocator;
use Nazca\Managers\Endpoints\EndpointInvocatorInterface;
use Nazca\Managers\Http\HttpRequestManager;
use Nazca\Managers\Http\HttpRequestManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

return [

    ContainerInterface::class => DI\Container::class,

    ServerRequestInterface::class => DI\get(ServerRequest::class),

    EmitterInterface::class => DI\get(SapiEmitter::class),

    EndpointInvocatorInterface::class => DI\get(EndpointInvocator::class),

    HttpRequestManagerInterface::class => DI\get(HttpRequestManager::class),
];
