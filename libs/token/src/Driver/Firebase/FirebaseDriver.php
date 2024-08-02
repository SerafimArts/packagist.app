<?php

declare(strict_types=1);

namespace Local\Token\Driver\Firebase;

use Local\Token\Algo;
use Local\Token\AlgoInterface;
use Local\Token\Driver\Driver;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Local\Token\Driver
 */
abstract readonly class FirebaseDriver extends Driver
{
    /**
     * @var non-empty-string
     */
    protected string $key;

    /**
     * @param non-empty-string $key
     */
    public function __construct(
        string $key,
        AlgoInterface $algo = Algo::DEFAULT,
        ClockInterface $clock = new NativeClock(),
    ) {
        $this->key = $key;

        parent::__construct($algo, $clock);
    }
}
