<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus;

interface QueryBusInterface
{
    public function get(object $query): mixed;
}
