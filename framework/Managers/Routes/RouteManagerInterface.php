<?php

declare(strict_types=1);

namespace Nazca\Managers\Routes;

interface RouteManagerInterface
{
    public function getRoutes(): \Iterator;
}
