<?php

declare(strict_types=1);

namespace Nazca\Exceptions\Manager\Endpoint;

use Throwable;

class RouteNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Route %s not found';

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
