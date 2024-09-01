<?php

declare(strict_types=1);

namespace App\Statistic\Application\Updates;

use App\Shared\Domain\Bus\CommandBusInterface;
use App\Statistic\Domain\Event\PackagesUpdateRequested;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * @api
 */
#[AsEventListener]
final readonly class PackagesUpdateRequestedHandler
{
    public function __construct(
        private CommandBusInterface $commands,
    ) {}

    public function __invoke(PackagesUpdateRequested $event): void
    {
        $this->commands->send(new AddPackagesUpdateCommand(
            ip: $event->ip,
            userAgent: $event->userAgent,
        ));
    }
}
