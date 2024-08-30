<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Type;

use App\Shared\Domain\Id\PackageId;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Shared\Infrastructure\Persistence\Type
 *
 * @template-extends UniversalUniqueIdType<PackageId>
 */
final class PackageIdType extends UniversalUniqueIdType
{
    protected static function getClass(): string
    {
        return PackageId::class;
    }
}
