<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Exception\Http;

interface HttpHeadersProviderInterface
{
    /**
     * @return iterable<non-empty-string, string>
     */
    public function getHttpHeaders(): iterable;
}
