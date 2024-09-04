<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Version\Reference;

use App\Shared\Domain\ValueObjectInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final readonly class DistReference extends Reference
{
    /**
     * @param non-empty-string $type
     * @param non-empty-string $url
     * @param non-empty-string|null $hash
     */
    public function __construct(
        string $type,
        string $url,
        #[ORM\Column(type: 'string', nullable: true)]
        public ?string $hash = null,
    ) {
        parent::__construct($type, $url);
    }

    public static function createEmpty(): self
    {
        // @phpstan-ignore-next-line
        return new self('', '', null);
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return parent::equals($object)
            && $this->hash === $object->hash;
    }
}
