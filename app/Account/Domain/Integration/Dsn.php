<?php

declare(strict_types=1);

namespace App\Account\Domain\Integration;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final readonly class Dsn implements \Stringable
{
    /**
     * @var non-empty-string
     */
    #[ORM\Column(name: 'dsn')]
    public string $uri;

    /**
     * @param non-empty-string $uri
     */
    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->uri;
    }
}
