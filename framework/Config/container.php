<?php

use Nazca\Managers\Endpoints\EndpointInvocator;
use Nazca\Managers\Endpoints\EndpointInvocatorInterface;
use Nazca\Managers\Http\HttpRequestManager;
use Nazca\Managers\Http\HttpRequestManagerInterface;
use Psr\Container\ContainerInterface;

return [

    ContainerInterface::class => DI\Container::class,

    EndpointInvocatorInterface::class => DI\get(EndpointInvocator::class),

    HttpRequestManagerInterface::class => DI\get(HttpRequestManager::class),
];
