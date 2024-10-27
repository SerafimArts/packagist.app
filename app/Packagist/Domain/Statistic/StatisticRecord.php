<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Statistic;

use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use Doctrine\ORM\Mapping as ORM;

abstract class StatisticRecord implements CreatedDateProviderInterface
{
    use CreatedDateProvider;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public protected(set) ?int $id = null;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(name: 'ip', type: 'string', nullable: false)]
    public protected(set) string $ip;

    /**
     * @param non-empty-string $ip
     */
    public function __construct(string $ip)
    {
        $this->ip = $ip;
    }
}
