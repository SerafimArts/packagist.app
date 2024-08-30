<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\CommandBus;

use App\Shared\Domain\Bus\CommandBusInterface;

abstract readonly class CommandBus implements CommandBusInterface {}
