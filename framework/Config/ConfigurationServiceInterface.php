<?php

namespace Nazca\Config;

interface ConfigurationServiceInterface
{
    /**
     * @return mixed
     */
    public function get(string $configurationKey);
}
