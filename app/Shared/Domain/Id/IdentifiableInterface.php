<?php

declare(strict_types=1);

namespace App\Shared\Domain\Id;

/**
 * @property-read IdInterface $id Provides an identifier of the entity.
 *
 * TODO Should be replaced by the property since PHP 8.4:
 *      ```
 *      public IdInterface $id { get; }
 *      ```
 */
interface IdentifiableInterface {}
