<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\EventBusInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class EventBus implements EventBusInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
    ) {}

    public function dispatch(object $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
