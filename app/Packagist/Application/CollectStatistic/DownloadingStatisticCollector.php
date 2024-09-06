<?php

declare(strict_types=1);

namespace App\Packagist\Application\CollectStatistic;

use App\Packagist\Domain\Statistic\DownloadsStatisticRecord;
use App\Packagist\Domain\Statistic\ReleaseDownloadsStatisticRecord;
use App\Packagist\Domain\Statistic\StatisticRecord;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DownloadingStatisticCollector
{
    /**
     * @var \SplQueue<StatisticRecord>
     */
    private \SplQueue $records;

    public function __construct(
        private EntityManagerInterface $em,
    ) {
        $this->records = new \SplQueue();
    }

    public function addDownloadingRecord(AddDownloadingRecordCommand $command): void
    {
        // Skip when command has been executed from the CI.
        if ($command->ci === true) {
            return;
        }

        $this->records->enqueue(new DownloadsStatisticRecord(
            ip: $command->ip,
            composerVersion: $command->composerVersion,
            phpVersion: $command->phpVersion,
            os: $command->operatingSystem,
        ));
    }

    public function addReleaseDownloadingRecord(AddReleaseDownloadingCommand $command): void
    {
        // Skip when command has been executed
        // from the CI or flag cannot be parsed.
        if ($command->info->ci !== false) {
            return;
        }

        $this->records->enqueue(new ReleaseDownloadsStatisticRecord(
            ip: $command->info->ip,
            name: $command->name,
            version: $command->version,
        ));
    }

    public function flush(): void
    {
        while (!$this->records->isEmpty()) {
            $record = $this->records->dequeue();

            $this->em->persist($record);
        }

        $this->em->flush();
    }
}
