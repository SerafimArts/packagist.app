<?php

declare(strict_types=1);

namespace App\Statistic\Domain\Event;

final readonly class PackageDownloadedEvent
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $version
     */
    public function __construct(
        public DownloadedEvent $parent,
        public string $name,
        public string $version,
    ) {}
}
