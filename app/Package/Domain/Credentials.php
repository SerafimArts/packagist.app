<?php

declare(strict_types=1);

namespace App\Package\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Credentials
{
    /**
     * Unique package name
     *
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string')]
    public string $name;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string')]
    public string $vendor;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $vendor
     */
    public function __construct(
        string $name,
        string $vendor,
    ) {
        $this->name = $name;
        $this->vendor = $vendor;
    }
}
