<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Serializer\Handler;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;

/**
 * @phpstan-type SubscriptionType array{
 *     direction: GraphNavigatorInterface::DIRECTION_*,
 *     format: non-empty-string,
 *     type: non-empty-string,
 *     method: non-empty-string
 * }
 */
abstract class SubscribingHandler implements SubscribingHandlerInterface
{
    /**
     * @return array{SubscriptionType, SubscriptionType}
     */
    abstract public static function getSubscribingMethods(): array;
}
