<?php

namespace Nazca\Managers\Endpoints;

use Psr\Http\Message\ResponseInterface;

interface EndpointInvocatorInterface
{
    public function invoke(): ResponseInterface;
}
