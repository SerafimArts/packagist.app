<?php

declare(strict_types=1);

namespace App\Packagist\Application\CollectStatistic;

final readonly class AddPackageDownloadingCommand
{
    public function __construct(
        public string $name,
        public AddDownloadingRecordCommand $info,
    ) {}
}
