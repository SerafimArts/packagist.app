<?php

declare(strict_types=1);

namespace App\Statistic\Application\Updates;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @api
 */
#[AsMessageHandler(method: 'onAddDownloadsCommand')]
#[AsMessageHandler(method: 'onAddPackageDownloadsCommand')]
#[AsEventListener(event: 'kernel.terminate', method: 'onTerminate')]
final readonly class AddDownloadsCommandHandler
{
    public function __construct(
        private DownloadsCollector $collector,
    ) {}

    /**
     * @api
     */
    public function onAddDownloadsCommand(AddDownloadsCommand $command): void
    {
        $this->collector->addDownloadedRecord(
            ip: $command->ip,
            userAgent: $command->userAgent,
        );
    }

    /**
     * @api
     */
    public function onAddPackageDownloadsCommand(AddPackageDownloadsCommand $command): void
    {
        $this->collector->addPackageDownloadedRecord(
            ip: $command->parent->ip,
            userAgent: $command->parent->userAgent,
            name: $command->name,
            version: $command->version,
        );
    }

    /**
     * @api
     */
    public function onTerminate(TerminateEvent $event): void
    {
        $this->collector->flush();
    }
}
