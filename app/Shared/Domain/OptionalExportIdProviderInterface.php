<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Id\ExportId;

/**
 * Any instance containing this interface provides an optional
 * export (that is, external) ID value.
 *
 * @property-read ExportId|null $exportId Provides an export id of the entity.
 *
 * TODO Should be replaced by the property since PHP 8.4:
 *      ```
 *      public ?ExportId $exportId { get; }
 *      ```
 */
interface OptionalExportIdProviderInterface {}
