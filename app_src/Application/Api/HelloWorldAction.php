<?php

namespace App\Application\Api;

use Symfony\Component\HttpFoundation\Response;

class HelloWorldAction
{
    public function __invoke()
    {
        return new Response('route / in controller invoke!');
    }

    public function test()
    {
        return new Response('in /test in controller test!');
    }
}
