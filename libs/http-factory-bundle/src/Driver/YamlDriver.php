<?php

declare(strict_types=1);

namespace Local\HttpFactory\Driver;

use Symfony\Component\Yaml\Yaml;

final readonly class YamlDriver extends Driver
{
    /**
     * A constant containing valid acceptable content-types that are responsible
     * for the yaml inside the response body.
     *
     * @var non-empty-list<non-empty-string>
     */
    private const SUPPORTED_CONTENT_TYPES_YAML = [
        'application/yaml',
        'application/yml',
        'application/x-yaml',
        'application/x-yml',
        'text/yaml',
        'text/yml',
        'text/x-yaml',
    ];

    /**
     * Contains {@see true} in case of `symfony/yaml` is available.
     */
    private bool $available;

    public function __construct()
    {
        $this->available = \class_exists(Yaml::class);
    }

    /**
     * @return non-empty-list<non-empty-string>
     */
    protected function getSupportedContentTypes(): array
    {
        return self::SUPPORTED_CONTENT_TYPES_YAML;
    }

    protected function isAvailable(): bool
    {
        return $this->available;
    }

    protected function fromString(string $data): array|object
    {
        try {
            $result = Yaml::parse($data, Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);
        } catch (\Throwable $e) {
            $message = 'An error occurred while parsing request yaml payload: ' . $e->getMessage();
            throw new \InvalidArgumentException($message, (int) $e->getCode());
        }

        if (\is_object($result) || \is_array($result)) {
            return $result;
        }

        return (array) $result;
    }

    protected function toString(mixed $data): string
    {
        /** @var string */
        return Yaml::dump($data, 4, 2);
    }
}
