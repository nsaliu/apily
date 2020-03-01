<?php

namespace Nazca\Exceptions\Config;

use Throwable;

class ConfigurationKeyNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Configuration key [%s] not found';

    public function __construct(string $keyName, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                $this->message,
                $keyName
            ),
            $code,
            $previous
        );
    }
}
