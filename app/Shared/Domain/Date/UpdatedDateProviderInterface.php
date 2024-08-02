<?php

declare(strict_types=1);

namespace App\Shared\Domain\Date;

/**
 * Each object that contains this interface supports the ability to obtain
 * information about the date of the last update of this object after saving
 * it to the database.
 *
 * @property \DateTimeImmutable|null $updatedAt Returns the date the object
 *           was last updated. Returns {@see null} if the object has never
 *           been updated.
 *
 * TODO Should be replaced by the property since PHP 8.4:
 *      ```
 *      // Interface
 *      public \DateTimeImmutable|null $updatedAt { get; set; }
 *      ```
 */
interface UpdatedDateProviderInterface {}
