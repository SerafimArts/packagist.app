<?php

declare(strict_types=1);

namespace App\Statistic\Domain;

use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @property-read int|null $id Annotation for PHP 8.4 autocompletion support
 * @property-read non-empty-string $ip Annotation for PHP 8.4 autocompletion support
 */
abstract class StatisticRecord implements CreatedDateProviderInterface
{
    use CreatedDateProvider;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public private(set) ?int $id = null;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(name: 'ip', type: 'string', nullable: false)]
    public private(set) string $ip;

    /**
     * @param non-empty-string $ip
     */
    public function __construct(string $ip)
    {
        $this->ip = $ip;
    }
}
