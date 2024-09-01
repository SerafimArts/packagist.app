<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\CommandBus;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class SymfonyMessengerCommandBus extends CommandBus
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {}

    public function send(object $command): mixed
    {
        try {
            $envelope = $this->bus->dispatch($command);
        } catch (HandlerFailedException $e) {
            foreach ($e->getWrappedExceptions() as $exception) {
                throw $exception;
            }

            throw $e;
        }

        $stamp = $envelope->last(HandledStamp::class);

        return $stamp?->getResult();
    }
}
