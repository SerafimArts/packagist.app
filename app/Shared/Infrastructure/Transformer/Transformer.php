<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Transformer;

/**
 * @template TInput of mixed
 * @template TOutput of mixed
 * @template-implements TransformerInterface<TInput, TOutput>
 */
abstract readonly class Transformer implements TransformerInterface
{
    /**
     * @template TArgKey of array-key
     *
     * @param iterable<TArgKey, TInput> $entries
     *
     * @return iterable<TArgKey, TOutput>
     *
     * @psalm-suppress TooManyArguments
     */
    public function map(iterable $entries, mixed ...$args): iterable
    {
        foreach ($entries as $i => $entry) {
            yield $i => $this->transform($entry, ...$args);
        }
    }

    /**
     * @param iterable<mixed, TInput> $entries
     *
     * @return iterable<array-key, TOutput>
     *
     * @psalm-suppress TooManyArguments
     */
    public function mapWithoutKeys(iterable $entries, mixed ...$args): iterable
    {
        foreach ($entries as $entry) {
            yield $this->transform($entry, ...$args);
        }
    }

    /**
     * @param TInput|null $entry
     *
     * @return TOutput|null
     *
     * @psalm-suppress TooManyArguments
     */
    public function optional(mixed $entry, mixed ...$args): mixed
    {
        if ($entry === null) {
            return $entry;
        }

        return $this->transform($entry, ...$args);
    }

    /**
     * @param iterable<mixed, TInput> $entries
     *
     * @return list<TOutput>
     *
     * @psalm-suppress TooManyArguments
     */
    public function mapToArray(iterable $entries, mixed ...$args): array
    {
        $result = [];

        foreach ($entries as $entry) {
            $result[] = $this->transform($entry, ...$args);
        }

        return $result;
    }
}
