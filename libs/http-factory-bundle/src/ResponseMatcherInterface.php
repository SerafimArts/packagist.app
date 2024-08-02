<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Symfony\Component\HttpFoundation\Request;

interface ResponseMatcherInterface extends ResponseEncoderInterface
{
    /**
     * Returns {@see true} in case of given {@see Request} provides
     * expected `accept` header or {@see false} instead.
     */
    public function isAcceptable(Request $request): bool;
}
