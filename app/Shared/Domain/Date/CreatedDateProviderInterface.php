<?php

declare(strict_types=1);

namespace App\Shared\Domain\Date;

/**
 * Each object that contains this interface supports the ability to obtain
 * information about the creation date of this object in the database.
 */
interface CreatedDateProviderInterface
{
    /**
     * Returns the creation date of the object.
     */
    public \DateTimeImmutable $createdAt { get; }
}
