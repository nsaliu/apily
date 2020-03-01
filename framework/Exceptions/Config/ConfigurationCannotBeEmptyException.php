<?php

namespace Nazca\Exceptions\Config;

class ConfigurationCannotBeEmptyException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Configuration cannot be empty';

    public function __construct()
    {
        parent::__construct(
            $this->message,
            0,
            null
        );
    }
}
