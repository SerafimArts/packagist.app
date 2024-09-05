<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Release\Reference;

use App\Packagist\Domain\Release\PackageRelease;
use App\Shared\Domain\ValueObjectInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
abstract readonly class Reference implements ValueObjectInterface
{
    /**
     * @param non-empty-string $type
     * @param non-empty-string $url
     */
    public function __construct(
        #[ORM\Column(type: 'string', options: ['default' => ''])]
        public string $type,
        #[ORM\Column(type: 'string', options: ['default' => ''])]
        public string $url,
    ) {}

    abstract public static function createEmpty(): self;

    /**
     * @internal for internal usage in {@see PackageRelease} properties.
     */
    public function isValid(): bool
    {
        return $this->type !== '' && $this->url !== '';
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $this === $object
            || ($object instanceof static
                && $this->type === $object->type
                && $this->url === $object->url);
    }

    public function __toString(): string
    {
        return \vsprintf('%s://%s', [
            $this->type,
            $this->url,
        ]);
    }
}
