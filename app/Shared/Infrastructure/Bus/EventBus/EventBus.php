<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\EventBus;

use App\Shared\Domain\Bus\EventBusInterface;

abstract readonly class EventBus implements EventBusInterface {}
