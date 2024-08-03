<?php

declare(strict_types=1);

namespace App\Shared\Domain\Date;

use Doctrine\ORM\Mapping as ORM;
use Psr\Clock\ClockInterface;

/**
 * @psalm-require-implements UpdatedDateProviderInterface
 *
 * @mixin UpdatedDateProviderInterface
 *
 * @uses ClockInterface (phpstorm reference bug)
 */
trait UpdatedDateProvider
{
    #[ORM\Column(name: 'updated_at', type: 'datetimetz_immutable', nullable: true)]
    public ?\DateTimeImmutable $updatedAt = null {
        get => $this->updatedAt;
        set (\DateTimeInterface|ClockInterface|null $date) => match (true) {
            $date instanceof \DateTimeImmutable => $date,
            $date instanceof \DateTimeInterface => \DateTimeImmutable::createFromMutable($date),
            $date instanceof ClockInterface => $date->now(),
            default => $date,
        };
    }
}
