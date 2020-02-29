<?php

namespace App\Application\Api;


use Laminas\Diactoros\Response\JsonResponse;

class HelloWorldAction
{
    public function __invoke()
    {
        return new JsonResponse('route / in controller invoke!');
    }

    public function test()
    {
        return new JsonResponse(['asd' => 'in /test in controller test!'], 200, [
            'Content-Type' => [ 'application/json' ]
        ]);
    }
}
