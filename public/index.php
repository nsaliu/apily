<?php

require __DIR__.'/../vendor/autoload.php';

use App\Factories\ContainerFactory;
use App\Managers\Http\HttpRequestManagerInterface;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ContainerFactory::createDependencyInjectionContainer();

/** @var HttpRequestManagerInterface $httpManager */
$httpManager = $container->get(HttpRequestManagerInterface::class);
$httpManager->handleRequest();
$httpManager->sendResponse();
$httpManager->end();
