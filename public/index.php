<?php

$start = microtime(true);

require __DIR__.'/../vendor/autoload.php';

use Nazca\Factories\ContainerFactory;
use Nazca\Managers\Http\HttpRequestManagerInterface;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ContainerFactory::createDependencyInjectionContainer();

/** @var HttpRequestManagerInterface $httpManager */
$httpManager = $container->get(HttpRequestManagerInterface::class);
$httpManager->handleRequest();
$httpManager->sendResponse();
$httpManager->end();

$end = microtime(true);

echo "<hr><br/>The code took " . ($end - $start) . " seconds to complete.";
