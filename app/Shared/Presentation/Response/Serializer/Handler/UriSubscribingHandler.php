<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\Serializer\Handler;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Psr\Http\Message\UriInterface;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Shared\Presentation\Response\Serializer\Handler
 */
final class UriSubscribingHandler implements SubscribingHandlerInterface
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
                'type' => UriInterface::class,
                'method' => 'serialize',
            ],
        ];
    }

    /**
     * @api
     */
    public function serialize(SerializationVisitorInterface $visitor, UriInterface $uri): mixed
    {
        return $visitor->visitString((string) $uri, [
            'name' => 'string',
        ]);
    }
}
