<?php

declare(strict_types=1);

namespace App\Statistic\Application\Updates;

final readonly class AddPackagesUpdateCommand
{
    /**
     * @param non-empty-string $ip
     * @param non-empty-string|null $userAgent
     */
    public function __construct(
        public string $ip,
        public ?string $userAgent = null,
    ) {}
}
