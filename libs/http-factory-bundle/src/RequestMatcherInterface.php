<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Symfony\Component\HttpFoundation\Request;

interface RequestMatcherInterface extends RequestDecoderInterface
{
    /**
     * Returns {@see true} in case of given {@see Request} provides
     * expected `content-type` header or {@see false} instead.
     */
    public function isProvides(Request $request): bool;
}
