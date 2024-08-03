<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

use App\Shared\Domain\DomainException;

class AccountNotFoundException extends DomainException
{
    public static function becauseInvalidLogin(string $login, ?\Throwable $prev = null): static
    {
        $message = 'Account with login "%s" not found';
        $message = \sprintf($message, $login);

        return new static($message, 0, $prev);
    }
}
