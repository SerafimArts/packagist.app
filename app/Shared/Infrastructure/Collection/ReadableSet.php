<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ReadableCollection as ReadableCollectionInterface;
use Doctrine\Common\Collections\Selectable as SelectableInterface;

/**
 * @template T of object
 *
 * @template-implements ReadableCollectionInterface<array-key, T>
 * @template-implements SelectableInterface<array-key, T>
 * @phpstan-consistent-constructor
 */
abstract class ReadableSet implements ReadableCollectionInterface, SelectableInterface
{
    /**
     * @param ReadableCollectionInterface<array-key, T> $delegate
     */
    public function __construct(
        protected readonly ReadableCollectionInterface $delegate = new ArrayCollection(),
    ) {}

    public static function for(ReadableCollectionInterface $ctx): static
    {
        return Reference::for($ctx, static fn (ReadableCollectionInterface $ctx): static
            => new static($ctx),
        );
    }

    /**
     * @template TArg of object
     *
     * @param list<TArg> $elements
     * @return static<TArg>
     */
    public static function fromArray(array $elements = []): static
    {
        return new static(new ArrayCollection($elements));
    }

    public function matching(Criteria $criteria): static
    {
        return new static($this->delegate->matching($criteria));
    }

    public function getIterator(): \Traversable
    {
        return $this->delegate->getIterator();
    }

    public function count(): int
    {
        return $this->delegate->count();
    }

    public function contains(mixed $element): bool
    {
        return $this->delegate->contains($element);
    }

    public function isEmpty(): bool
    {
        return $this->delegate->isEmpty();
    }

    public function containsKey(int|string $key): bool
    {
        return $this->delegate->containsKey($key);
    }

    public function get(int|string $key): mixed
    {
        return $this->delegate->get($key);
    }

    public function getKeys(): array
    {
        return $this->delegate->getKeys();
    }

    public function getValues(): array
    {
        return $this->delegate->getValues();
    }

    public function toArray(): array
    {
        return $this->delegate->toArray();
    }

    public function first(): mixed
    {
        return $this->delegate->first();
    }

    public function last(): mixed
    {
        return $this->delegate->last();
    }

    public function key(): int|string
    {
        return $this->delegate->key();
    }

    public function current(): mixed
    {
        return $this->delegate->current();
    }

    public function next(): mixed
    {
        return $this->delegate->next();
    }

    public function slice(int $offset, ?int $length = null): array
    {
        return $this->delegate->slice($offset, $length);
    }

    public function exists(\Closure $p): bool
    {
        return $this->delegate->exists($p);
    }

    public function filter(\Closure $p): static
    {
        return new static($this->delegate->filter($p));
    }

    public function map(\Closure $func): static
    {
        return new static($this->delegate->map($func));
    }

    public function partition(\Closure $p): array
    {
        [$a, $b] = $this->delegate->partition($p);

        return [new static($a), new static($b)];
    }

    public function forAll(\Closure $p): bool
    {
        return $this->delegate->forAll($p);
    }

    public function indexOf(mixed $element): mixed
    {
        return $this->delegate->indexOf($element);
    }

    public function findFirst(\Closure $p): mixed
    {
        return $this->delegate->findFirst($p);
    }

    public function reduce(\Closure $func, mixed $initial = null): mixed
    {
        return $this->delegate->reduce($func, $initial);
    }
}
