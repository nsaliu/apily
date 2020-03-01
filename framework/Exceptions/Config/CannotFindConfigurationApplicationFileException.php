<?php

declare(strict_types=1);

namespace Nazca\Exceptions\Config;

class CannotFindConfigurationApplicationFileException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Cannot find configuration application file';

    public function __construct()
    {
        parent::__construct(
            $this->message,
            0,
            null
        );
    }
}
