<?php

namespace App\Managers\Http;

use App\Managers\Endpoints\EndpointInvocatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HttpRequestManager implements HttpRequestManagerInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var EndpointInvocatorInterface
     */
    private $endpointInvocator;

    public function __construct(
        EndpointInvocatorInterface $endpointInvocator
    ) {
        $this->request = Request::createFromGlobals();

        $this->endpointInvocator = $endpointInvocator;
        $this->endpointInvocator->setRequest($this->request);
    }

    public function handleRequest(): void
    {
        $this->response = $this->endpointInvocator->invoke();
    }

    public function sendResponse(): Response
    {
        return $this->response->send();
    }

    public function end(): void
    {
    }
}
