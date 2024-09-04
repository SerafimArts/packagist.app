<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Version\Reference;

use App\Shared\Domain\ValueObjectInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final readonly class SourceReference extends Reference
{
    /**
     * @param non-empty-string $type
     * @param non-empty-string $url
     * @param non-empty-string $hash
     */
    public function __construct(
        string $type,
        string $url,
        #[ORM\Column(type: 'string', options: ['default' => ''])]
        public string $hash,
    ) {
        parent::__construct($type, $url);
    }

    /**
     * @internal for internal usage in {@see PackageVersion} properties.
     */
    public function isValid(): bool
    {
        return parent::isValid() && $this->hash !== '';
    }

    public static function createEmpty(): self
    {
        // @phpstan-ignore-next-line
        return new self('', '', '');
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return parent::equals($object)
            && $this->hash === $object->hash;
    }
}
