<?php

declare(strict_types=1);

namespace App\Account\Domain;

enum Role: string
{
    case SuperAdmin = 'ROLE_SUPER_ADMIN';
    case Admin = 'ROLE_ADMIN';

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->value;
    }

    /**
     * @param non-empty-string $name
     */
    public function is(string $name): bool
    {
        return \strtolower($this->value) === \strtolower($name);
    }

    /**
     * @param non-empty-string $name
     */
    public function isNot(string $name): bool
    {
        return !$this->is($name);
    }
}
