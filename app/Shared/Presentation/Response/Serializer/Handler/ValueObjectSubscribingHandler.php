<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\Serializer\Handler;

use App\Shared\Domain\ValueObjectInterface;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Shared\Presentation\Response\Serializer\Handler
 */
final class ValueObjectSubscribingHandler implements SubscribingHandlerInterface
{
    /**
     * @return list<array<array-key, mixed>>
     */
    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => ValueObjectInterface::class,
                'method' => 'serialize',
            ],
        ];
    }

    /**
     * @api
     */
    public function serialize(SerializationVisitorInterface $visitor, ValueObjectInterface $vo): mixed
    {
        return $visitor->visitString((string) $vo, [
            'name' => 'string',
        ]);
    }
}
