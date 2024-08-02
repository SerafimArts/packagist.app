<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\Serializer\Listener;

use JMS\Serializer\EventDispatcher\ObjectEvent;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Shared\Presentation\Response\Serializer\Listener
 */
abstract readonly class EventListener
{
    /**
     * @param class-string $class
     */
    protected static function instanceOf(ObjectEvent $event, string $class): bool
    {
        return \is_a(static::getClass($event), $class, true);
    }

    /**
     * @return class-string
     */
    protected static function getClass(ObjectEvent $event): string
    {
        /** @var array{name: class-string, params: list<mixed>} $type */
        $type = $event->getType();

        return $type['name'];
    }
}
