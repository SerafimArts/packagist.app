<?php

declare(strict_types=1);

namespace Local\Token\Driver;

use Local\Token\Algo;
use Local\Token\AlgoInterface;
use Local\Token\Driver\Common\DateIntervalFactory;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Local\Token\Driver
 */
abstract readonly class Driver
{
    public function __construct(
        protected AlgoInterface $algo = Algo::DEFAULT,
        protected ClockInterface $clock = new NativeClock(),
    ) {}

    /**
     * @throws \Exception
     */
    protected function createDateInterval(mixed $interval): \DateInterval
    {
        $factory = new DateIntervalFactory();

        return $factory->create($interval);
    }

    /**
     * @throws \Exception
     */
    protected function createExpiresAt(\DateTimeImmutable $now, mixed $interval): \DateTimeImmutable
    {
        return $now->add($this->createDateInterval($interval));
    }
}
