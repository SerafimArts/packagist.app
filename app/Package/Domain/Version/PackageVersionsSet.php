<?php

declare(strict_types=1);

namespace App\Package\Domain\Version;

use App\Shared\Infrastructure\Collection\Set;

/**
 * @template-extends Set<PackageVersion>
 *
 * @property PackageVersionsSet $released Annotation for PHP 8.4 autocompletion support
 * @property PackageVersionsSet $dev Annotation for PHP 8.4 autocompletion support
 */
final class PackageVersionsSet extends Set
{
    public PackageVersionsSet $released {
        get => $this->filter(static fn (PackageVersion $v): bool => $v->isRelease);
    }

    public PackageVersionsSet $dev {
        get => $this->filter(static fn (PackageVersion $v): bool => !$v->isRelease);
    }
}
