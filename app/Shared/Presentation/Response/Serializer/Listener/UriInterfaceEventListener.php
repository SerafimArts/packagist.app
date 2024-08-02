<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\Serializer\Listener;

use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use Psr\Http\Message\UriInterface;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Shared\Presentation\Response\Serializer\Listener
 */
final readonly class UriInterfaceEventListener extends EventListener
{
    public function __invoke(PreSerializeEvent $event): void
    {
        if (!self::instanceOf($event, UriInterface::class)) {
            return;
        }

        $event->setType(UriInterface::class);
    }
}
