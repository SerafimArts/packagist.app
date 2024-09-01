<?php

declare(strict_types=1);

namespace Local\Set\Internal;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal Local\Set
 *
 * @template TKey of array-key
 * @template T
 * @template-extends ArrayCollection<TKey, T>
 */
final class ReferencedArrayCollection extends ArrayCollection
{
    /**
     * @param array<TKey, T> $elements
     */
    public function __construct(array &$elements = [])
    {
        parent::__construct($elements);

        $accessor = (function (array &$value): void {
            $this->elements = &$value;
        })
            ->bindTo($this, parent::class);

        $accessor($elements);
    }
}
