<?php

declare(strict_types=1);

namespace Local\Token\Reader;

/**
 * @template T of mixed
 */
interface TokenReaderInterface
{
    /**
     * Returns {@see true} in case of parser may parse the token value from
     * container, {@see false} otherwise.
     *
     * @param T $container
     */
    public function isReadable(mixed $container): bool;

    /**
     * Returns token string from container or empty string in case of token
     * cannot be parsed from request.
     *
     * @param T $container
     */
    public function read(mixed $container): string;
}
