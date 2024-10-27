<?php

declare(strict_types=1);

namespace App\Packagist\Domain;

use Local\Set\Set;

/**
 * @template-extends Set<Release>
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
