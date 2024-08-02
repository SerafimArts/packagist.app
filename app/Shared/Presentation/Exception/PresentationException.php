<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Exception;

use App\Shared\Application\Exception\ApplicationException;
use App\Shared\Domain\Exception\DomainException;

/**
 * @template TData of mixed
 * @template-implements PublicDataProviderInterface<TData>
 *
 * @phpstan-consistent-constructor
 */
class PresentationException extends \LogicException implements
    PublicDataProviderInterface,
    PublicMessageProviderInterface
{
    private mixed $publicData = null;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromApplicationException(ApplicationException $e): static
    {
        return new static($e->getMessage(), $e->getCode(), $e);
    }

    public static function fromDomainException(DomainException $e): static
    {
        return new static($e->getMessage(), $e->getCode(), $e);
    }

    public function getPublicMessage(): string
    {
        return $this->getMessage();
    }

    /**
     * @template TDataParam of mixed
     *
     * @phpstan-self-out self<TDataParam>
     *
     * @param TDataParam $data
     *
     * @return self<TData>
     */
    public function setPublicData(mixed $data): self
    {
        $this->publicData = $data;

        return $this;
    }

    public function getPublicData(): mixed
    {
        return $this->publicData;
    }
}
