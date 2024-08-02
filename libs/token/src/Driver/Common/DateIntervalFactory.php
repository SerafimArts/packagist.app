<?php

declare(strict_types=1);

namespace Local\Token\Driver\Common;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Local\Token\Driver
 */
final class DateIntervalFactory
{
    /**
     * @throws \Exception
     */
    public function create(mixed $interval): \DateInterval
    {
        switch (true) {
            case $interval instanceof \DateInterval:
                return $interval;
            case \is_string($interval):
                return new \DateInterval($interval);
            default:
                $type = \is_object($interval)
                    ? \get_class($interval)
                    : \var_export($interval, true);

                throw new \InvalidArgumentException(\sprintf(
                    'Unsupported interval type %s',
                    $type,
                ));
        }
    }
}
