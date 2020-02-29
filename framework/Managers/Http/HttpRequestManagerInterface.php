<?php

namespace Nazca\Managers\Http;

use Symfony\Component\HttpFoundation\Response;

interface HttpRequestManagerInterface
{
    public function handleRequest(): void;

    public function sendResponse(): Response;

    public function end(): void;
}
