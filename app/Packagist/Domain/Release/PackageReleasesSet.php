<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Release;

use Local\Set\Set;

/**
 * @template-extends Set<PackageRelease>
 *
 * @property PackageReleasesSet $released Annotation for PHP 8.4 autocompletion support
 * @property PackageReleasesSet $dev Annotation for PHP 8.4 autocompletion support
 */
final class PackageReleasesSet extends Set
{
    public self $released {
        get => $this->filter(static fn(PackageRelease $v): bool => $v->isRelease);
    }

    public self $dev {
        get => $this->filter(static fn(PackageRelease $v): bool => !$v->isRelease);
    }
}
