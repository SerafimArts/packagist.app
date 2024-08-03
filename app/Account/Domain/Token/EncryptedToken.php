<?php

declare(strict_types=1);

namespace App\Account\Domain\Token;

use App\Shared\Domain\ValueObjectInterface;

readonly class EncryptedToken implements ValueObjectInterface
{
    /**
     * @param non-empty-string $value
     */
    public function __construct(
        protected string $value,
    ) {}

    /**
     * @return non-empty-string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof self
            && $this->value === $object->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
