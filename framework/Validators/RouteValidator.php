<?php

namespace Nazca\Validators;

use Nazca\Exceptions\Validator\HttpMethodNotSupportedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class RouteValidator
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Route
     */
    private $route;

    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    /**
     * @throws HttpMethodNotSupportedException
     */
    public function validate(Route $route): void
    {
        $this->route = $route;

        $this->validateHttpMethod($route);
    }

    /**
     * @throws HttpMethodNotSupportedException
     */
    private function validateHttpMethod(): void
    {
        if (!in_array($this->request->getMethod(), $this->getAvailableMethods())) {
            throw new HttpMethodNotSupportedException(
                $this->request->getPathInfo()
            );
        }
    }

    private function getAvailableMethods(): array
    {
        return explode('|', $this->route->getMethods()[0]);
    }
}
