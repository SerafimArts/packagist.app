<?php

declare(strict_types=1);

namespace App\Shared\Domain\Id;

/**
 * @property-read IdInterface $id Provides an identifier of the entity.
 */
interface IdentifiableInterface
{
    /**
     * Provides an identifier of the entity.
     */
    public IdInterface $id { get; }
}
