<?php

declare(strict_types=1);

namespace App\Packagist\Infrastructure\Listener;

use App\Packagist\Application\CollectStatistic\DownloadingStatisticCollector;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'kernel.terminate')]
final readonly class FlushStatisticEventListener
{
    public function __construct(
        private DownloadingStatisticCollector $collector,
    ) {}

    public function __invoke(): void
    {
        $this->collector->flush();
    }
}
