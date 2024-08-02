<?php

declare(strict_types=1);

namespace Local\Token;

use Local\Token\Exception\KeyNotFoundException;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

abstract readonly class TokenFactory
{
    public function __construct(
        protected ClockInterface $clock = new NativeClock()
    ) {}

    /**
     * @param non-empty-string $pathname
     */
    protected function tryRead(string $pathname): string
    {
        $contents = @\file_get_contents($pathname);

        if ($contents === false) {
            throw KeyNotFoundException::fromInvalidPathname($pathname);
        }

        return $contents;
    }
}
