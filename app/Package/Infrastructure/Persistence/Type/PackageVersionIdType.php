<?php

declare(strict_types=1);

namespace App\Package\Infrastructure\Persistence\Type;

use App\Shared\Domain\Id\PackageVersionId;
use App\Shared\Infrastructure\Persistence\Type\UniversalUniqueIdType;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Infrastructure\Persistence\Type
 *
 * @template-extends UniversalUniqueIdType<PackageVersionId>
 */
final class PackageVersionIdType extends UniversalUniqueIdType
{
    protected static function getClass(): string
    {
        return PackageVersionId::class;
    }
}
