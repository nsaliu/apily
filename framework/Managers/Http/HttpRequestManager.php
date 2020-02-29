<?php

namespace Nazca\Managers\Http;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Nazca\Managers\Endpoints\EndpointInvocatorInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

final class HttpRequestManager implements HttpRequestManagerInterface
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EmitterInterface
     */
    private $emitter;

    public function __construct(
        ContainerInterface $container,
        EmitterInterface $emitter
    ) {
        $this->container = $container;
        $this->emitter = $emitter;

        $this->request = ServerRequestFactory::fromGlobals();

        $this->container->set(
            ServerRequest::class,
            $this->request
        );
    }

    public function handleRequest(): void
    {
        /** @var EndpointInvocatorInterface $endpointInvocator */
        $endpointInvocator = $this->container->get(EndpointInvocatorInterface::class);

        $this->response = $endpointInvocator->invoke();
    }

    public function sendResponse(): void
    {
        $this->emitter->emit($this->response);
    }

    public function end(): void
    {
    }
}
