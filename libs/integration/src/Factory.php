<?php

declare(strict_types=1);

namespace Local\Integration;

use Local\Integration\Exception\InvalidProviderException;
use Psr\Container\ContainerInterface;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal Local\Integration
 */
final readonly class Factory implements FactoryInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    public function getClient(string $type): ClientInterface
    {
        try {
            $client = $this->container->get($type);
        } catch (\Throwable $e) {
            throw InvalidProviderException::becauseInvalidProvider(
                provider: $type,
                prev: $e,
            );
        }

        return $client;
    }
}
