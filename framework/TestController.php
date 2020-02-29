<?php

namespace App;

use Symfony\Component\HttpFoundation\Response;

final class TestController
{
    public function test()
    {
        return new Response('in controller...!');
    }
}
