<?php

namespace Nazca\Config;

use Nazca\Exceptions\Config\CannotFindConfigurationApplicationFileException;
use Nazca\Exceptions\Config\ConfigurationCannotBeEmptyException;
use Nazca\Exceptions\Config\ConfigurationKeyNotFoundException;
use Symfony\Component\Yaml\Yaml;

class ConfigurationService implements ConfigurationServiceInterface
{
    private const CONFIG_FILE_PATH = '/../../app_config/app.yaml';

    private const MANDATORY_PARAMS = [
        ConfigEnum::ROUTES_FILE_NAME,
        ConfigEnum::DEBUG,
        ConfigEnum::CACHE_DIRECTORY_PATH,
        ConfigEnum::ACTIONS_NAMESPACE,
        ConfigEnum::DIC_COMPILATION_ENABLED,
        ConfigEnum::DIC_COMPILATION_DIRECTORY,
        ConfigEnum::DIC_CONFIGURATION_DIRECTORY_PATH,
    ];

    /**
     * @var array
     */
    private $configuration = [];

    /**
     * @throws CannotFindConfigurationApplicationFileException
     * @throws ConfigurationCannotBeEmptyException
     * @throws ConfigurationKeyNotFoundException
     */
    public function __construct()
    {
        $configurationFilePath = __DIR__ . self::CONFIG_FILE_PATH;

        if (!file_exists($configurationFilePath)) {
            throw new CannotFindConfigurationApplicationFileException();
        }

        $contents = file_get_contents($configurationFilePath);

        if ($contents === false) {
            throw new CannotFindConfigurationApplicationFileException();
        }

        $this->configuration = Yaml::parse(
            file_get_contents($configurationFilePath)
        );

        if (empty($this->configuration)) {
            throw new ConfigurationCannotBeEmptyException();
        }

        $this->validateMandatoryFields();
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConfigurationKeyNotFoundException
     */
    public function get(string $configurationKey)
    {
        $this->validateMandatoryFields();

        return $this->extractConfigurationValue($this->configuration, $configurationKey);
    }

    public function getDebugEnabled(): bool
    {
        if (!is_bool($this->configuration['app']['config']['dependency_injection_container']['compilation_enabled'])) {
            throw new \RuntimeException('app.config.debug must have only boolean value');
        }

        return $this->configuration['app']['config']['debug'];
    }

    public function getActionsNamespace(): string
    {
        return (string) $this->configuration['app']['config']['actions_namespace'];
    }

    public function getCacheDirectoryPath(): string
    {
        return (string) $this->configuration['app']['config']['cache_directory_path'];
    }

    public function getRoutesDirectory(): string
    {
        return (string) $this->configuration['app']['config']['routes']['directory'];
    }

    public function getRoutesFileName(): string
    {
        return (string) $this->configuration['app']['config']['routes']['file'];
    }

    public function getDICConfigurationDirectoryPath(): string
    {
        return (string) $this->configuration['app']['config']['dependency_injection_container']['configuration_directory_path'];
    }

    public function getDICConfigurationFilePath(): string
    {
        return (string) $this->configuration['app']['config']['dependency_injection_container']['configuration_file_path'];
    }

    public function getDICCompilationEnabled(): bool
    {
        if (!is_bool($this->configuration['app']['config']['dependency_injection_container']['compilation_enabled'])) {
            throw new \RuntimeException('app.config.dependency_injection_container.compilation_enabled must have only boolean value');
        }

        return (bool) $this->configuration['app']['config']['dependency_injection_container']['compilation_enabled'];
    }

    public function getDICCompilationDirectory(): string
    {
        return (string) $this->configuration['app']['config']['dependency_injection_container']['compilation_directory'];
    }

    /**
     * @throws ConfigurationKeyNotFoundException
     */
    private function validateMandatoryFields(): void
    {
        foreach (self::MANDATORY_PARAMS as $param) {
            if ($this->extractConfigurationValue($this->configuration, $param) === null) {
                throw new ConfigurationKeyNotFoundException($param);
            }
        }
    }

    /**
     * @param mixed $key
     * @return mixed
     */
    private function extractConfigurationValue(array $configuration, $key)
    {
        if (is_string($key)) {
            $key = explode('.', $key);
        }

        for ($i = 0; $i < count($key); $i++) {
            if (array_key_exists($key[$i], $configuration)) {
                if (count($key) === 1) {
                    return $configuration[$key[$i]];
                }

                return $this->extractConfigurationValue($configuration[$key[$i]], array_slice($key, 1));
            }
        }

        return null;
    }
}
