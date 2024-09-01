<?php

declare(strict_types=1);

namespace App\Account\Domain;

use Local\Set\Set;

/**
 * @template-extends Set<Role>
 */
final class RoleSet extends Set implements \Stringable
{
    public function __toString(): string
    {
        $items = $this->map(static fn(Role $role): string
            => $role->getName()
        )
            ->toArray();

        return \implode(', ', $items);
    }
}
