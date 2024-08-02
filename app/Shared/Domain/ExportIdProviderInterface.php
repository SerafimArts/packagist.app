<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Id\ExportId;

/**
 * Same as {@see OptionalExportIdProviderInterface}, but
 * export ID cannot be {@see null}.
 *
 * @property-read ExportId $exportId Provides an export id of the entity.
 *
 * TODO Should be replaced by the property since PHP 8.4:
 *      ```
 *      public ExportId $exportId { get; }
 *      ```
 */
interface ExportIdProviderInterface extends
    OptionalExportIdProviderInterface {}
