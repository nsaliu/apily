<?php

declare(strict_types=1);

namespace Nazca\Exceptions\Manager\Endpoint;

use Throwable;

class ActionClassNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'The class %s is missing';

    public function __construct(string $className, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                $this->message,
                $className
            ),
            $code,
            $previous
        );
    }
}
