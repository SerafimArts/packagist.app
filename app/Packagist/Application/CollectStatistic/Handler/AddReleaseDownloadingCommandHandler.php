<?php

declare(strict_types=1);

namespace App\Packagist\Application\CollectStatistic\Handler;

use App\Packagist\Application\CollectStatistic\AddReleaseDownloadingCommand;
use App\Packagist\Application\CollectStatistic\DownloadingStatisticCollector;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class AddReleaseDownloadingCommandHandler
{
    public function __construct(
        private DownloadingStatisticCollector $collector,
    ) {}

    public function __invoke(AddReleaseDownloadingCommand $command): void
    {
        $this->collector->addReleaseDownloadingRecord($command);
    }
}
