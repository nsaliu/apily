<?php

declare(strict_types=1);

namespace Nazca\Config;

final class ConfigEnum
{
    public const DEBUG = 'app.config.debug';

    public const ACTIONS_NAMESPACE = 'app.config.actions_namespace';

    public const CACHE_DIRECTORY_PATH = 'app.config.cache_directory_path';

    public const ROUTES_DIRECTORY_PATH = 'app.config.routes.directory';

    public const ROUTES_FILE_NAME = 'app.config.routes.file';

    public const DIC_COMPILATION_ENABLED = 'app.config.dependency_injection_container.compilation_enabled';

    public const DIC_COMPILATION_DIRECTORY = 'app.config.dependency_injection_container.compilation_directory';

    public const DIC_CONFIGURATION_DIRECTORY_PATH = 'app.config.dependency_injection_container.configuration_directory_path';
}
