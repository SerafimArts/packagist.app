<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Transformer;

/**
 * @template TInput of mixed
 * @template TOutput of mixed
 */
interface TransformerInterface
{
    /**
     * @param TInput $entry
     *
     * @return TOutput
     */
    public function transform(mixed $entry): mixed;
}
