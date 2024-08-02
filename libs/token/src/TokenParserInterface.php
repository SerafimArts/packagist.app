<?php

declare(strict_types=1);

namespace Local\Token;

/**
 * @template T of array{
 *     iat: \DateTimeImmutable,
 *     exp?: \DateTimeImmutable,
 *     typ: non-empty-string,
 *     alg: non-empty-string,
 *     ...
 * }
 */
interface TokenParserInterface
{
    /**
     * @return T
     */
    public function parse(string $token): array;
}
