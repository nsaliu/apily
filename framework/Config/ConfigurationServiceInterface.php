<?php

declare(strict_types=1);

namespace Nazca\Config;

interface ConfigurationServiceInterface
{
    /**
     * @return mixed
     */
    public function get(string $configurationKey);
}
