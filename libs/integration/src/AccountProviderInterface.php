<?php

declare(strict_types=1);

namespace Local\Integration;

use Local\Integration\Exception\InvalidCodeException;

interface AccountProviderInterface
{
    /**
     * @param non-empty-string $code
     * @throws InvalidCodeException
     */
    public function getAccount(string $code): AccountInterface;
}
