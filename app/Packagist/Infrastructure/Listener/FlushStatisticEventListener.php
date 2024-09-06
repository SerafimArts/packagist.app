<?php

declare(strict_types=1);

namespace App\Packagist\Infrastructure\Listener;

use App\Packagist\Application\CollectStatistic\DownloadingStatisticCollector;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'kernel.terminate')]
final readonly class FlushStatisticEventListener
{
    public function __construct(
        private DownloadingStatisticCollector $collector,
        private LoggerInterface $logger,
    ) {}

    public function __invoke(): void
    {
        try {
            $this->collector->flush();
        } catch (\Throwable $e) {
            $this->logger->error('Unable to collect downloading statistic', [
                'error' => $e,
            ]);
        }
    }
}
