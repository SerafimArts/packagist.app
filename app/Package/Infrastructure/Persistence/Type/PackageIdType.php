<?php

declare(strict_types=1);

namespace App\Package\Infrastructure\Persistence\Type;

use App\Package\Domain\PackageId;
use App\Shared\Infrastructure\Persistence\Type\UniversalUniqueIdType;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Infrastructure\Persistence\Type
 */
final class PackageIdType extends UniversalUniqueIdType
{
    protected static function getClass(): string
    {
        return PackageId::class;
    }
}
