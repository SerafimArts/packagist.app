<?php

declare(strict_types=1);

namespace Local\HttpFactory\Driver;

use MessagePack\MessagePack;

final readonly class MessagePackDriver extends Driver
{
    /**
     * A constant containing valid content-types that are responsible
     * for the msgpack inside the response body.
     *
     * @var non-empty-list<non-empty-string>
     */
    private const SUPPORTED_CONTENT_TYPES_MSGPACK = [
        'application/msgpack',
        'application/x-msgpack',
    ];

    /**
     * Contains {@see true} in case of `rybakit/msgpack` is available.
     */
    private bool $available;

    public function __construct()
    {
        $this->available = \class_exists(MessagePack::class);
    }

    /**
     * @return non-empty-list<non-empty-string>
     */
    protected function getSupportedContentTypes(): array
    {
        return self::SUPPORTED_CONTENT_TYPES_MSGPACK;
    }

    protected function isAvailable(): bool
    {
        return $this->available;
    }

    protected function fromString(string $data): array|object
    {
        try {
            /** @psalm-suppress MixedAssignment */
            $result = MessagePack::unpack($data);
        } catch (\Throwable $e) {
            $message = 'An error occurred while parsing request msgpack payload: ' . $e->getMessage();
            throw new \InvalidArgumentException($message, (int) $e->getCode());
        }

        if (\is_object($result) || \is_array($result)) {
            /** @var array|object */
            return $result;
        }

        return (array) $result;
    }

    protected function toString(mixed $data): string
    {
        return MessagePack::pack($data);
    }
}
