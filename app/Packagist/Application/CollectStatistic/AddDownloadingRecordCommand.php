<?php

declare(strict_types=1);

namespace App\Packagist\Application\CollectStatistic;

final readonly class AddDownloadingRecordCommand
{
    /**
     * @param non-empty-string $ip
     * @param non-empty-string|null $composerVersion
     * @param non-empty-string|null $phpVersion
     * @param non-empty-string|null $operatingSystem
     */
    public function __construct(
        public string $ip,
        public ?string $composerVersion = null,
        public ?string $phpVersion = null,
        public ?string $operatingSystem = null,
        public ?bool $ci = null,
    ) {}
}
