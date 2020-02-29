<?php

namespace Nazca\Managers\Endpoints;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface EndpointInvocatorInterface
{
    public function setRequest(Request $request): void;

    public function invoke(): Response;
}
