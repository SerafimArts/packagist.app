<?php

declare(strict_types=1);

namespace App\Package\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final readonly class Credentials implements \Stringable
{
    /**
     * Unique package name.
     *
     * @var non-empty-lowercase-string
     */
    #[ORM\Column(type: 'string')]
    public string $name;

    /**
     * @var non-empty-lowercase-string|null
     */
    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $vendor;

    /**
     * @param non-empty-string|null $vendor
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        ?string $vendor = null,
    ) {
        $this->vendor = \strtolower($vendor);
        $this->name = \strtolower($name);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        if ($this->vendor === null) {
            return $this->name;
        }

        return \vsprintf('%s/%s', [
            $this->vendor,
            $this->name,
        ]);
    }
}
