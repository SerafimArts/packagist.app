<?php

declare(strict_types=1);

namespace App\Account\Domain\Integration;

use App\Shared\Domain\Uri;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ConnectionInfo extends Uri
{
    /**
     * @var non-empty-string
     */
    #[ORM\Column(name: 'dsn')]
    public readonly string $value;
}
