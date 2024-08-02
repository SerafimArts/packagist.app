<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Type;

use App\Shared\Domain\Id\ExportId;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Shared\Infrastructure\Persistence\Type
 *
 * @template-extends ObjectIdType<ExportId>
 */
final class ExportIdType extends ObjectIdType
{
    protected static function getClass(): string
    {
        return ExportId::class;
    }
}
