<?php

declare(strict_types=1);

namespace App\Packagist\Application\CollectStatistic;

final readonly class AddReleaseDownloadingCommand
{
    public function __construct(
        public string $version,
        public AddPackageDownloadingCommand $package,
    ) {}
}
