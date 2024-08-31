<?php

declare(strict_types=1);

namespace Local\Integration;

use Psr\Http\Message\UriInterface;

interface AuthUriProviderInterface
{
    public function getUri(): UriInterface;
}
