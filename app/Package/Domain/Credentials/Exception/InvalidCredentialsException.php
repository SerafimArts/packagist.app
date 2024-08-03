<?php

declare(strict_types=1);

namespace App\Package\Domain\Credentials\Exception;

use App\Shared\Domain\DomainException;

class InvalidCredentialsException extends DomainException
{
    public static function fromInvalidPackage(string $package, ?\Throwable $prev = null): self
    {
        $message = \sprintf('Invalid package name "%s"', $package);

        return new static($message, previous: $prev);
    }
}
