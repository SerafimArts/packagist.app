<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Collection;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Shared\Infrastructure\Collection
 *
 * @template T of object
 * @template U of object
 */
final class Reference
{
    /**
     * @var \WeakMap<T, U>|null
     */
    private static ?\WeakMap $memory = null;

    /**
     * @param T $reference
     * @param callable(T):U $then
     * @return U
     */
    public static function for(object $reference, callable $then): object
    {
        self::$memory ??= new \WeakMap();

        return self::$memory[$reference] ??= $then($reference);
    }
}
