<?php

declare(strict_types=1);

namespace Local\HttpFactory\Driver;

final readonly class JsonDriver extends Driver
{
    public const DEFAULT_JSON_ENCODE_FLAGS = \JSON_HEX_TAG
        | \JSON_HEX_APOS
        | \JSON_HEX_AMP
        | \JSON_HEX_QUOT;

    /**
     * A constant containing valid content-types that are
     * responsible for the json inside the response body.
     *
     * @var non-empty-list<non-empty-string>
     */
    private const SUPPORTED_CONTENT_TYPES_JSON = [
        'application/json',
        'application/vnd.api+json',
        'text/javascript',
    ];

    /**
     * Contains {@see true} in case of `ext-json` is available.
     */
    private bool $available;

    public function __construct(
        private bool $debug = false,
    ) {
        $this->available = \extension_loaded('json');
    }

    /**
     * @return non-empty-list<non-empty-string>
     */
    protected function getSupportedContentTypes(): array
    {
        return self::SUPPORTED_CONTENT_TYPES_JSON;
    }

    protected function isAvailable(): bool
    {
        return $this->available;
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function fromString(string $data): array
    {
        try {
            return (array) \json_decode($data, true, depth: 32, flags: \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $message = 'An error occurred while parsing request json payload: ' . $e->getMessage();
            throw new \InvalidArgumentException($message, $e->getCode());
        }
    }

    /**
     * @throws \JsonException
     */
    protected function toString(mixed $data): string
    {
        $flags = self::DEFAULT_JSON_ENCODE_FLAGS;

        if ($this->debug) {
            $flags |= \JSON_PRETTY_PRINT;
        }

        /** @var non-empty-string */
        return \json_encode($data, $flags | \JSON_THROW_ON_ERROR);
    }
}
