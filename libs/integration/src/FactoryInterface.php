<?php

declare(strict_types=1);

namespace Local\Integration;

use Local\Integration\Exception\InvalidProviderException;

interface FactoryInterface
{
    /**
     * @param non-empty-string $type
     *
     * @throws InvalidProviderException
     */
    public function getClient(string $type): ClientInterface;
}
