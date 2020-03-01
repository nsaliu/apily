<?php

declare(strict_types=1);

namespace Nazca\Exceptions\Factory;

use Throwable;

class ContainerDefinitionMustBeAnArrayException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Container definition Must be an array, %s given in %s definition';

    /**
     * @param mixed $container
     */
    public function __construct(string $container, $type, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                $this->message,
                gettype($type),
                $container
            ),
            $code,
            $previous
        );
    }
}
