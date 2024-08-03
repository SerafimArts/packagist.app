<?php

declare(strict_types=1);

namespace App\Shared\Application\Exception;

use App\Shared\Domain\DomainException;

/**
 * @phpstan-consistent-constructor
 */
abstract class ApplicationException extends DomainException
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromDomainException(DomainException $e): static
    {
        return new static($e->getMessage(), $e->getCode(), $e);
    }
}
