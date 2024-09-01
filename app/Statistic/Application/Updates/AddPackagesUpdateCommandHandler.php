<?php

declare(strict_types=1);

namespace App\Statistic\Application\Updates;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @api
 */
#[AsMessageHandler(method: 'onAddPackagesUpdateCommand')]
#[AsEventListener(event: 'kernel.terminate', method: 'onTerminate')]
final readonly class AddPackagesUpdateCommandHandler
{
    public function __construct(
        private PackagesUpdateStatisticCollector $collector,
    ) {}

    /**
     * @api
     */
    public function onAddPackagesUpdateCommand(AddPackagesUpdateCommand $command): void
    {
        $this->collector->collect($command->ip, $command->userAgent);
    }

    /**
     * @api
     */
    public function onTerminate(TerminateEvent $event): void
    {
        $this->collector->flush();
    }
}
