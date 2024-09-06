<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Event;

use App\Packagist\Domain\Statistic\ClientInfo;

final readonly class DownloadedEvent
{
    public function __construct(
        public ClientInfo $info,
    ) {}
}
