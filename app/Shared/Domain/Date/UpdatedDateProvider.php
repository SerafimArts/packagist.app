<?php

declare(strict_types=1);

namespace App\Shared\Domain\Date;

use Doctrine\ORM\Mapping as ORM;

/**
 * @psalm-require-implements UpdatedDateProviderInterface
 *
 * @mixin UpdatedDateProviderInterface
 *
 * @property-read \DateTimeImmutable|null $updatedAt Annotation for PHP 8.4 autocompletion support
 */
trait UpdatedDateProvider
{
    #[ORM\Column(name: 'updated_at', type: 'datetimetz_immutable', nullable: true)]
    public ?\DateTimeImmutable $updatedAt = null;
}
