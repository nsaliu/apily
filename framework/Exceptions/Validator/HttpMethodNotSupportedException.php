<?php

declare(strict_types=1);

namespace Nazca\Exceptions\Validator;

use Throwable;

class HttpMethodNotSupportedException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'The HTTP Method is not supported for route %s';

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
