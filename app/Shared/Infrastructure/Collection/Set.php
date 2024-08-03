<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as CollectionInterface;

/**
 * @template T of object
 *
 * @template-extends ReadableSet<T>
 * @template-implements CollectionInterface<array-key, T>
 * @phpstan-consistent-constructor
 */
abstract class Set extends ReadableSet implements CollectionInterface
{
    /**
     * @param CollectionInterface<array-key, T> $delegate
     */
    public function __construct(
        CollectionInterface $delegate = new ArrayCollection(),
    ) {
        parent::__construct($delegate);
    }

    protected function onAdd(object $entity): void
    {
        // TODO
    }

    public function add(mixed $element): void
    {
        $this->onAdd($element);

        $this->delegate->add($element);
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
        $this->onAdd($value);

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
        $this->onAdd($value);

        $this->delegate->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->delegate->offsetUnset($offset);
    }
}
