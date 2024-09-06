<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Event;

use App\Packagist\Domain\Statistic\ClientInfo;
use App\Shared\Domain\Id\PackageId;

final readonly class PackageDownloadedEvent
{
    public function __construct(
        public PackageId $id,
        public ClientInfo $info,
    ) {}
}
