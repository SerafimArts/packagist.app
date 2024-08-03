<?php

declare(strict_types=1);

namespace App\Package\Domain;

use App\Shared\Infrastructure\Collection\Set;

/**
 * @template-extends Set<PackageVersion>
 */
final class PackageVersionsSet extends Set
{
    public PackageVersionsSet $released {
        get => $this->filter(static fn (int $i, PackageVersion $v): bool => $v->isRelease);
    }

    public PackageVersionsSet $dev {
        get => $this->filter(static fn (int $i, PackageVersion $v): bool => !$v->isRelease);
    }
}
