<?php

declare(strict_types=1);

namespace App\Package\Application\Repository;

final readonly class RepositoryInfo
{
    /**
     * @param non-empty-string $metadata Metadata
     */
    public function __construct(
        public string $metadata,
    ) {}
}
