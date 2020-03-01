<?php

namespace Nazca\Factories;

use DI\ContainerBuilder;
use Nazca\Config\ConfigurationService;
use Nazca\Config\ConfigurationServiceInterface;
use Nazca\Exceptions\Factory\ContainerDefinitionMustBeAnArrayException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;

final class ContainerFactory
{
    public static function createDependencyInjectionContainer(
        ConfigurationServiceInterface $configurationService
    ): ContainerInterface
    {
        $builder = new ContainerBuilder();

        $builder->useAutowiring(true);

        /** @var ConfigurationService $configurationService */
        if ($configurationService->getDICCompilationEnabled()) {
            $builder->enableCompilation(
                __DIR__ . '/../../' . $configurationService->getDICCompilationDirectory()
            );
        }

        $builder->addDefinitions(
            self::retrieveDependencyInjectionConfiguration($configurationService)
        );

        return $builder->build();
    }

    /**
     * @throws ContainerDefinitionMustBeAnArrayException
     */
    private static function retrieveDependencyInjectionConfiguration(
        ConfigurationServiceInterface $configurationService
    ): array
    {
        /** @var ConfigurationService $configurationService */
        $fileLocator = new FileLocator([__DIR__ . '/../../' . $configurationService->getDICConfigurationDirectoryPath()]);

        $frameworkContainerConfiguration = require $fileLocator->locate('framework_container.php');

        if (!is_array($frameworkContainerConfiguration)) {
            throw new ContainerDefinitionMustBeAnArrayException('framework_container.php', $frameworkContainerConfiguration);
        }

        $appContainerConfiguration = require $fileLocator->locate('app_container.php');

        if (!is_array($appContainerConfiguration)) {
            throw new ContainerDefinitionMustBeAnArrayException('app_container.php', $appContainerConfiguration);
        }

        return array_merge($frameworkContainerConfiguration, $appContainerConfiguration);
    }
}
