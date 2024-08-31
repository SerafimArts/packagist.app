<?php

declare(strict_types=1);

namespace App\Account\Domain\Integration;

use Doctrine\ORM\Mapping as ORM;
use Psr\Http\Message\UriInterface;

#[ORM\Embeddable]
final class Dsn implements \Stringable
{
    #[ORM\Column(name: 'dsn', type: UriInterface::class)]
    public readonly UriInterface $uri;

    /** @var non-empty-string */
    public string $provider {
        get => $this->uri->getScheme();
    }

    public function __construct(UriInterface $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return (string) $this->uri;
    }
}
