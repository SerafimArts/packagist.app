<?php

declare(strict_types=1);

namespace App\Shared\Domain\Date;

use Doctrine\ORM\Mapping as ORM;

/**
 * @psalm-require-implements CreatedDateProviderInterface
 *
 * @mixin CreatedDateProviderInterface
 */
trait CreatedDateProvider
{
    /**
     * @readonly impossible to specify "readonly" attribute natively due
     *           to a Doctrine bug https://github.com/doctrine/orm/issues/9863
     */
    #[ORM\Column(name: 'created_at', type: 'datetimetz_immutable', nullable: false, options: [
        'default' => 'CURRENT_TIMESTAMP',
    ])]
    public \DateTimeImmutable $createdAt;
}