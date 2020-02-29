<?php

namespace Nazca\Exceptions\Manager\Route;

use Throwable;

class MissingControllerInRouteDefinitionException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'The Controller definition is missing in route.yaml definition for route %s';

    public function __construct(string $routeName, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                $this->message,
                $routeName
            ),
            $code,
            $previous
        );
    }
}
