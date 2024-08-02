<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

class InvalidAccountCredentialsException extends DomainException
{
    public static function becauseInvalidPassword(string $login, ?\Throwable $prev = null): static
    {
        $message = 'Invalid account "%s" password';
        $message = \sprintf($message, $login);

        return new static($message, 0, $prev);
    }
}
