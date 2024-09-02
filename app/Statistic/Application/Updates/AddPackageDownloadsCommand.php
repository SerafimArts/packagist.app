<?php

declare(strict_types=1);

namespace App\Statistic\Application\Updates;

final readonly class AddPackageDownloadsCommand
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $version
     */
    public function __construct(
        public AddDownloadsCommand $parent,
        public string $name,
        public string $version,
    ) {}
}
