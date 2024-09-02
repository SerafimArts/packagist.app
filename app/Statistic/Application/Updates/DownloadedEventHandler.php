<?php

declare(strict_types=1);

namespace App\Statistic\Application\Updates;

use App\Shared\Domain\Bus\CommandBusInterface;
use App\Statistic\Domain\Event\DownloadedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * @api
 */
#[AsEventListener]
final readonly class DownloadedEventHandler
{
    public function __construct(
        private CommandBusInterface $commands,
    ) {}

    public function __invoke(DownloadedEvent $event): void
    {
        $this->commands->send(new AddDownloadsCommand(
            ip: $event->ip,
            userAgent: $event->userAgent,
        ));
    }
}
