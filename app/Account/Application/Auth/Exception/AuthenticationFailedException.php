<?php

declare(strict_types=1);

namespace App\Account\Application\Auth\Exception;

use App\Shared\Application\Exception\ApplicationException;

class AuthenticationFailedException extends ApplicationException
{
    public static function becauseInvalidCredentials(string $login, ?\Throwable $prev = null): static
    {
        $message = 'Invalid account "%s" credentials';
        $message = \sprintf($message, $login);

        return new static($message, 0, $prev);
    }
}
