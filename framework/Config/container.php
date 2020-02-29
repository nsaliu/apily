<?php

use App\Managers\Endpoints\EndpointInvocator;
use App\Managers\Endpoints\EndpointInvocatorInterface;
use App\Managers\Http\HttpRequestManager;
use App\Managers\Http\HttpRequestManagerInterface;

return [

    EndpointInvocatorInterface::class => DI\get(EndpointInvocator::class),

    HttpRequestManagerInterface::class => DI\get(HttpRequestManager::class),
];
