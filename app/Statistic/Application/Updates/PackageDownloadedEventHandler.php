<?php

declare(strict_types=1);

namespace App\Statistic\Application\Updates;

use App\Shared\Domain\Bus\CommandBusInterface;
use App\Statistic\Domain\Event\PackageDownloadedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * @api
 */
#[AsEventListener]
final readonly class PackageDownloadedEventHandler
{
    public function __construct(
        private CommandBusInterface $commands,
    ) {}

    public function __invoke(PackageDownloadedEvent $event): void
    {
        $this->commands->send(new AddPackageDownloadsCommand(
            parent: new AddDownloadsCommand(
                ip: $event->parent->ip,
                userAgent: $event->parent->userAgent,
            ),
            name: $event->name,
            version: $event->version,
        ));
    }
}
