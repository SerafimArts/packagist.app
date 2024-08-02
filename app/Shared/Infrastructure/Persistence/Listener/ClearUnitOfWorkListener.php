<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

/**
 * Clears Unit of Work after the HTTP kernel terminates.
 */
#[AsEventListener(priority: 0)]
final readonly class ClearUnitOfWorkListener
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    public function __invoke(TerminateEvent $e): void
    {
        $this->em->clear();
    }
}
