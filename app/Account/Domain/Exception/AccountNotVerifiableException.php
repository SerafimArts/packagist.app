<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

use App\Shared\Domain\DomainException;

class AccountNotVerifiableException extends DomainException
{
    public static function becausePasswordIsEmpty(string $login, ?\Throwable $prev = null): static
    {
        $message = 'Account "%s" does not contain password';
        $message = \sprintf($message, $login);

        return new static($message, 0, $prev);
    }
}
