<?php

declare(strict_types=1);

namespace App\Package\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Credentials implements \Stringable
{
    /**
     * Unique package name.
     *
     * @var non-empty-lowercase-string
     */
    #[ORM\Column(type: 'string')]
    public string $name;

    /**
     * @var non-empty-lowercase-string
     */
    #[ORM\Column(type: 'string')]
    public string $vendor;

    /**
     * @param non-empty-string $vendor
     * @param non-empty-string $name
     */
    public function __construct(
        string $vendor,
        string $name,
    ) {
        $this->vendor = \strtolower($vendor);
        $this->name = \strtolower($name);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return \vsprintf('%s/%s', [
            $this->vendor,
            $this->name,
        ]);
    }
}
