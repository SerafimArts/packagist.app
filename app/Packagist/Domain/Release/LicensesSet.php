<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Release;

use Local\Set\Set;

/**
 * @template-extends Set<non-empty-string>
 */
final class LicensesSet extends Set
{
    #[\Override]
    protected function onAdd(mixed $entry): bool
    {
        return parent::onAdd($entry) && $entry !== '';
    }
}
