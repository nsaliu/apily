<?php

namespace Nazca\Factories;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;

final class ContainerFactory
{
    public static function createDependencyInjectionContainer(): ContainerInterface
    {
        $builder = new ContainerBuilder();

        $builder->useAutowiring(true);

        $builder->enableCompilation(
            self::getContainerCacheDirectoryPath()
        );

        $builder->addDefinitions(
            self::retrieveDependencyInjectionConfiguration()
        );

        return $builder->build();
    }

    /**
     * @return mixed
     */
    private static function retrieveDependencyInjectionConfiguration()
    {
        $fileLocator = new FileLocator([__DIR__ . '/../Config']);
        return include $fileLocator->locate('container.php');
    }

    private static function getContainerCacheDirectoryPath(): string
    {
        return __DIR__ . '/../../app_data/_private/cache/container';
    }
}
