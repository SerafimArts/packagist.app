<?php

declare(strict_types=1);

namespace App\Account\Application\Auth\Exception;

use App\Shared\Application\Exception\ApplicationException;

class AccountAlreadyRegisteredException extends ApplicationException
{
    public static function becauseAccountAlreadyExists(string $login, ?\Throwable $prev = null): static
    {
        $message = 'Account "%s" already has been registered';
        $message = \sprintf($message, $login);

        return new static($message, 0, $prev);
    }
}
