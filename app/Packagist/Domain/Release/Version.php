<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Release;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final readonly class Version implements \Stringable
{
    #[ORM\Column(name: 'version_normalized', type: 'string')]
    public string $normalized;

    /**
     * @param non-empty-string $value
     * @param non-empty-string|null $normalized
     */
    public function __construct(
        #[ORM\Column(name: 'version', type: 'string')]
        public string $value,
        ?string $normalized = null,
    ) {
        $this->normalized = $normalized ?? $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
