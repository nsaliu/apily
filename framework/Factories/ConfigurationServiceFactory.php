<?php

namespace Nazca\Factories;

use Nazca\Config\ConfigurationService;
use Nazca\Config\ConfigurationServiceInterface;

final class ConfigurationServiceFactory
{
    public static function createConfigurationService(): ConfigurationServiceInterface
    {
        return new ConfigurationService();
    }
}
