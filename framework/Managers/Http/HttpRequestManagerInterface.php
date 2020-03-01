<?php

declare(strict_types=1);

namespace Nazca\Managers\Http;

interface HttpRequestManagerInterface
{
    public function handleRequest(): void;

    public function sendResponse(): void;

    public function end(): void;
}
