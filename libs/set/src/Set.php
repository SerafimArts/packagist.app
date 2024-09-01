<?php

declare(strict_types=1);

namespace Local\Set;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as CollectionInterface;

/**
 * @template T of mixed
 * @template-extends ReadableSet<T>
 * @template-implements CollectionInterface<array-key, T>
 *
 * @phpstan-consistent-constructor
 */
class Set extends ReadableSet implements CollectionInterface
{
    /**
     * @param CollectionInterface<array-key, T> $delegate
     */
    public function __construct(
        CollectionInterface $delegate = new ArrayCollection(),
    ) {
        parent::__construct($delegate);
    }

    protected function onAdd(mixed $entry): bool
    {
        return !$this->delegate->contains($entry);
    }

    public function add(mixed $element): void
    {
        if ($this->onAdd($element)) {
            $this->delegate->add($element);
        }
    }

    public function clear(): void
    {
        $this->delegate->clear();
    }

    public function remove(int|string $key): mixed
    {
        return $this->delegate->remove($key);
    }

    public function removeElement(mixed $element): bool
    {
        return $this->delegate->removeElement($element);
    }

    public function set(int|string $key, mixed $value): void
    {
        if (!$this->onAdd($value)) {
            $this->delegate->removeElement($value);
        }

        $this->delegate->set($key, $value);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->delegate->offsetExists($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->delegate->offsetGet($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->delegate->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->delegate->offsetUnset($offset);
    }
}
