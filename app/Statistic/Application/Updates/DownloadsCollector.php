<?php

declare(strict_types=1);

namespace App\Statistic\Application\Updates;

use App\Packagist\Domain\Name\NameParser;
use App\Statistic\Domain\DownloadsStatisticRecord;
use App\Statistic\Domain\PackageDownloadsStatisticRecord;
use App\Statistic\Domain\StatisticRecord;
use Doctrine\ORM\EntityManagerInterface;
use Local\UserAgent\ParserInterface as ComposerUserAgentParserInterface;

final class DownloadsCollector
{
    /**
     * @var list<StatisticRecord>
     */
    private array $records = [];

    public function __construct(
        private readonly ComposerUserAgentParserInterface $userAgentParser,
        private readonly NameParser $nameParser,
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @param non-empty-string $ip
     * @param non-empty-string $name
     * @param non-empty-string $version
     */
    public function addPackageDownloadedRecord(
        string $ip,
        ?string $userAgent,
        string $name,
        string $version,
    ): void {
        $info = $this->userAgentParser->parse($userAgent ?? '');

        // Skip when command has been executed from the CI
        if ($info !== null && $info->ci === true) {
            return;
        }

        try {
            $parsed = $this->nameParser->parse($name);

            // Skip when package is not owned.
            // That is vendor name has not been provided.
            if (!$parsed->isOwned) {
                return;
            }
        } catch (\Throwable) {
            // Skip when package name has not been parsed
            return;
        }

        $this->records[] = new PackageDownloadsStatisticRecord(
            ip: $ip,
            name: $parsed,
            version: $version,
        );
    }

    /**
     * @param non-empty-string $ip
     */
    public function addDownloadedRecord(
        string $ip,
        ?string $userAgent,
    ): void {
        $info = $this->userAgentParser->parse($userAgent ?? '');

        // 1) Skip in case of user-agent is not parsable
        // 2) Skip when command has been executed from the CI
        if ($info === null || $info->ci === true) {
            return;
        }

        $this->records[] = new DownloadsStatisticRecord(
            ip: $ip,
            composerVersion: $info->composerVersion,
            phpVersion: $info->phpVersion,
            os: $info->os,
        );
    }

    public function flush(): void
    {
        while ($this->records !== []) {
            $next = \array_shift($this->records);

            $this->em->persist($next);
        }

        $this->em->flush();
    }
}
