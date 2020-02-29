<?php

namespace Nazca\Exceptions\Manager\Route;

use Throwable;

class MissingActionDefinitionInRouteException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'The Action definition is missing in route.yaml for route %s';

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
