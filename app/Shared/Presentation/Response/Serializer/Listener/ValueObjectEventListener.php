<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\Serializer\Listener;

use App\Shared\Domain\ValueObjectInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Shared\Presentation\Response\Serializer\Listener
 */
final readonly class ValueObjectEventListener extends EventListener
{
    public function __invoke(PreSerializeEvent $event): void
    {
        if (!self::instanceOf($event, ValueObjectInterface::class)) {
            return;
        }

        $event->setType(ValueObjectInterface::class);
    }
}
