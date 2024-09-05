<?php

declare(strict_types=1);

namespace App\Packagist\Domain;

use Local\Set\Set;

/**
 * @template-extends Set<Release>
 *
 * @property ReleasesSet $released Annotation for PHP 8.4 autocompletion support
 * @property ReleasesSet $dev Annotation for PHP 8.4 autocompletion support
 */
final class ReleasesSet extends Set
{
    public self $released {
        get => $this->filter(static fn(Release $v): bool => $v->isRelease);
    }

    public self $dev {
        get => $this->filter(static fn(Release $v): bool => !$v->isRelease);
    }
}
