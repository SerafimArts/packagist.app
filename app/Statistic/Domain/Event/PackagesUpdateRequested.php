<?php

declare(strict_types=1);

namespace App\Statistic\Domain\Event;

final readonly class PackagesUpdateRequested
{
    /**
     * @param non-empty-string $ip
     * @param non-empty-string|null $userAgent
     */
    public function __construct(
        public string $ip,
        public ?string $userAgent,
    ) {}
}
