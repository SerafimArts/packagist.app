<?php

declare(strict_types=1);

namespace Local\Token;

/**
 * @template T of array<non-empty-string, mixed>
 */
interface TokenBuilderInterface
{
    /**
     * @param T $payload
     *
     * @return non-empty-string
     */
    public function build(array $payload, mixed $expiresAt = null): string;
}
