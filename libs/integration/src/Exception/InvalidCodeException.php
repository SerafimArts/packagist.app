<?php

declare(strict_types=1);

namespace Local\Integration\Exception;

final class InvalidCodeException extends \InvalidArgumentException
{
    public static function becauseInvalidCode(string $code, ?\Throwable $prev = null): self
    {
        $message = 'Invalid authentication code "%s"';
        $message = \sprintf($message, $code);

        return new self($message, 0, $prev);
    }
}
