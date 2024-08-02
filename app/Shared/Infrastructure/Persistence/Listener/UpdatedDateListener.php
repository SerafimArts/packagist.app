<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Listener;

use App\Shared\Domain\Date\UpdatedDateProviderInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;

/**
 * Each object that implements the {@see UpdatedDateProviderInterface} interface before
 * UPDATE data in the database will also update its update date using the system
 * date returned from the interface's {@see ClockInterface} implementation.
 */
#[AsDoctrineListener(event: Events::preUpdate)]
final readonly class UpdatedDateListener
{
    public function __construct(
        private ClockInterface $clock,
    ) {}

    /**
     * @param LifecycleEventArgs<ObjectManager> $event
     */
    public function preUpdate(LifecycleEventArgs $event): void
    {
        $target = $event->getObject();

        if ($target instanceof UpdatedDateProviderInterface) {
            // @phpstan-ignore-next-line
            $target->updatedAt = $this->clock->now();
        }
    }
}
