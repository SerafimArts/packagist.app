<?php

declare(strict_types=1);

namespace App\Packagist\Application\CollectStatistic\Handler;

use App\Packagist\Application\CollectStatistic\AddDownloadingRecordCommand;
use App\Packagist\Application\CollectStatistic\DownloadingStatisticCollector;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class AddDownloadingRecordCommandHandler
{
    public function __construct(
        private DownloadingStatisticCollector $collector,
    ) {}

    public function __invoke(AddDownloadingRecordCommand $command): void
    {
        $this->collector->addDownloadingRecord($command);
    }
}
