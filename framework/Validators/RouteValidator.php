<?php

declare(strict_types=1);

namespace Nazca\Validators;

use Laminas\Diactoros\ServerRequest;
use Nazca\Exceptions\Validator\HttpMethodNotSupportedException;
use Symfony\Component\Routing\Route;

class RouteValidator
{
    /**
     * @var ServerRequest
     */
    private $request;

    /**
     * @var Route
     */
    private $route;

    public function __construct(
        ServerRequest $request
    ) {
        $this->request = $request;
    }

    /**
     * @throws HttpMethodNotSupportedException
     */
    public function validate(Route $route): void
    {
        $this->route = $route;

        $this->validateHttpMethod();
    }

    /**
     * @throws HttpMethodNotSupportedException
     */
    private function validateHttpMethod(): void
    {
        if (!in_array($this->request->getMethod(), $this->getAvailableMethods())) {
            throw new HttpMethodNotSupportedException($this->request->getUri()->getPath());
        }
    }

    private function getAvailableMethods(): array
    {
        return explode('|', $this->route->getMethods()[0]);
    }
}
