<?php

declare(strict_types=1);

namespace App\Package\Application\Metadata;

use Psr\Http\Message\UriInterface;

final readonly class PackagesListInfo
{
    public function __construct(
        public UriInterface $metadata,
    ) {}
}
