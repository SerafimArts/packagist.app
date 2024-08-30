<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\EventBus;

use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class PsrDispatcherEventBus extends EventBus
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
    ) {}

    public function dispatch(object $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
