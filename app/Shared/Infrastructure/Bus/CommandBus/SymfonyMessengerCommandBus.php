<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\CommandBus;

use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyMessengerCommandBus extends CommandBus
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {}

    public function send(object $command): void
    {
        $this->bus->dispatch($command);
    }
}
