<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Serializer\Subscriber;

use JMS\Serializer\Context;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\Metadata\ClassMetadata;

abstract readonly class EventSubscriber implements EventSubscriberInterface
{
    /**
     * @return list<array{
     *     event: Events::*,
     *     method: non-empty-string
     * }>
     */
    abstract public static function getSubscribedEvents(): array;

    /**
     * @param array{name?: non-empty-string} $type
     */
    protected function getClassMetadata(array $type, Context $ctx): ?ClassMetadata
    {
        $factory = $ctx->getMetadataFactory();

        if (!isset($type['name'])) {
            return null;
        }

        $result = $factory->getMetadataForClass($type['name']);

        if (!$result instanceof ClassMetadata) {
            return null;
        }

        return $result;
    }
}
