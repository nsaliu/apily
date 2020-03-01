<?php

declare(strict_types=1);

namespace Nazca\Exceptions\Manager\Endpoint;

use Throwable;

class ActionMethodNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Action method % not found for Action %s';

    public function __construct(string $methodName, string $actionClass, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                $this->message,
                $methodName,
                $actionClass
            ),
            $code,
            $previous
        );
    }
}
