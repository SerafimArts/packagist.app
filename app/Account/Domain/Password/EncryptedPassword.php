<?php

declare(strict_types=1);

namespace App\Account\Domain\Password;

use App\Shared\Domain\ValueObjectInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EncryptedPassword implements ValueObjectInterface
{
    /**
     * @var non-empty-string|null
     */
    #[ORM\Column(name: 'password', type: 'string', nullable: true)]
    public ?string $hash = null;

    /**
     * @param non-empty-string|null $hash
     */
    public function __construct(?string $hash = null)
    {
        $this->hash = $hash;
    }

    public static function empty(): self
    {
        return new self();
    }

    /**
     * @api
     */
    public function isPasswordProtected(): bool
    {
        return $this->hash !== null;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $this === $object
            || ($object instanceof static && $this->hash === $object->hash);
    }

    public function __toString(): string
    {
        return static::class . '@' . \spl_object_hash($this);
    }
}
