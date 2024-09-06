<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Event;

use App\Packagist\Domain\Statistic\ClientInfo;
use App\Shared\Domain\Id\PackageReleaseId;

final readonly class ReleaseDownloadedEvent
{
    public function __construct(
        public PackageReleaseId $id,
        public ClientInfo $info,
    ) {}
}
