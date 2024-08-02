<?php

declare(strict_types=1);

namespace App\Shared\Domain\Date;

/**
 * Each object that contains this interface supports the ability to obtain
 * information about the creation date of this object in the database.
 *
 * @property-read \DateTimeImmutable $createdAt Returns the creation date
 *                of the object.
 *
 * TODO Should be replaced by the property since PHP 8.4:
 *      ```
 *      // Interface
 *      public \DateTimeImmutable $createdAt { get; }
 *      ```
 */
interface CreatedDateProviderInterface {}
