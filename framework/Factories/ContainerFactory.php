<?php

namespace Nazca\Factories;

use DI\ContainerBuilder;
use Nazca\Config\ConfigurationService;
use Nazca\Config\ConfigurationServiceInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\PhpFileLoader;

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
     * @return mixed
     */
    private static function retrieveDependencyInjectionConfiguration(
        ConfigurationServiceInterface $configurationService
    )
    {
        /** @var ConfigurationService $configurationService */
        $fileLocator = new FileLocator([__DIR__ . '/../../' . $configurationService->getDICConfigurationDirectoryPath()]);
        return require $fileLocator->locate($configurationService->getDICConfigurationFilePath());
    }
}
