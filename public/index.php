<?php

declare(strict_types=1);

//$start = microtime(true);

require __DIR__.'/../vendor/autoload.php';

use Nazca\Factories\ConfigurationServiceFactory;
use Nazca\Factories\ContainerFactory;
use Nazca\Managers\Http\HttpRequestManagerInterface;
use Psr\Container\ContainerInterface;

$configuration = ConfigurationServiceFactory::createConfigurationService();

/** @var ContainerInterface $container */
$container = ContainerFactory::createDependencyInjectionContainer($configuration);

/** @var HttpRequestManagerInterface $httpManager */
$httpManager = $container->get(HttpRequestManagerInterface::class);

$httpManager->handleRequest();
$httpManager->sendResponse();
$httpManager->end();

//$end = microtime(true);

//echo "<hr><br/>The code took " . ($end - $start) . " seconds to complete.";
