<?php

declare(strict_types=1);

namespace App\Account\Domain\Integration;

use App\Shared\Domain\Dsn;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ConnectionInfo extends Dsn
{
    /**
     * @var non-empty-string
     */
    #[ORM\Column(name: 'dsn')]
    public readonly string $value;
}
