<?php

declare(strict_types=1);

namespace Local\Integration;

class GenericAccount implements AccountInterface
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string|null $login
     * @param non-empty-string|null $email
     * @param non-empty-string|null $avatar
     * @param array<non-empty-string, mixed> $attributes
     */
    public function __construct(
        public readonly string $id,
        public readonly ?string $login = null,
        public readonly ?string $email = null,
        public readonly ?string $avatar = null,
        public readonly array $attributes = [],
    ) {}
}
