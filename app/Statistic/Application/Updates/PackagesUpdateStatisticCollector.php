<?php

declare(strict_types=1);

namespace App\Statistic\Application\Updates;

use App\Statistic\Domain\PackagesUpdateStatisticRecord;
use Doctrine\ORM\EntityManagerInterface;
use Local\UserAgent\ParserInterface;

final class PackagesUpdateStatisticCollector
{
    /**
     * @var list<PackagesUpdateStatisticRecord>
     */
    private array $records = [];

    public function __construct(
        private readonly ParserInterface $parser,
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @param non-empty-string $ip
     */
    public function collect(string $ip, ?string $userAgent): void
    {
        $info = $this->parser->parse($userAgent ?? '');

        // Skip in case of user-agent is not parsable
        // Or command has been executed from the CI
        if ($info === null || $info->ci === true) {
            return;
        }

        $this->records[] = new PackagesUpdateStatisticRecord(
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
