<?php

declare(strict_types=1);

namespace Local\Integration\Exception;

final class InvalidProviderException extends \InvalidArgumentException
{
    public static function becauseInvalidProvider(string $provider, ?\Throwable $prev = null): self
    {
        $message = 'Unsupported integration provider "%s"';
        $message = \sprintf($message, $provider);

        return new self($message, 0, $prev);
    }
}
