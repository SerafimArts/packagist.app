<?php

declare(strict_types=1);

namespace App\Packagist\Domain;

use App\Shared\Domain\ValueObjectInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Name implements ValueObjectInterface
{
    /**
     * @var non-empty-lowercase-string
     */
    #[ORM\Column(name: 'name', type: 'string')]
    public readonly string $value;

    /**
     * @var non-empty-lowercase-string|null
     */
    #[ORM\Column(name: 'vendor', type: 'string', nullable: true)]
    public readonly ?string $vendor;

    /**
     * @api
     */
    public bool $isOwned {
        get => $this->vendor !== null;
    }

    /**
     * @param non-empty-string $name
     * @param non-empty-string|null $vendor
     */
    public function __construct(
        string $name,
        ?string $vendor = null,
    ) {
        $this->vendor = \is_string($vendor) ? \strtolower($vendor) : null;
        $this->value = \strtolower($name);
    }

    public static function create(string|\Stringable $name): self
    {
        if ($name instanceof Name) {
            return clone $name;
        }

        return new Name((string) $name);
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $this === $object
            || ($object instanceof self
                && $this->value === $object->value
                && $this->vendor === $object->vendor);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        if ($this->vendor === null) {
            return $this->value;
        }

        return \vsprintf('%s/%s', [
            $this->vendor,
            $this->value,
        ]);
    }
}
