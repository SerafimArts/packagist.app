<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Listener;

use App\Shared\Domain\Date\CreatedDateProviderInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;

/**
 * Each object that implements the {@see CreatedDateProviderInterface} interface will
 * force the creation date to be initialized using the system date returned
 * from the {@see ClockInterface} implementation of the interface before
 * SAVING data to the database.
 *
 * @api
 */
#[AsDoctrineListener(event: Events::prePersist)]
final readonly class CreatedDateListener
{
    public function __construct(
        private ClockInterface $clock,
    ) {}

    /**
     * @param LifecycleEventArgs<ObjectManager> $event
     *
     * @throws \ReflectionException
     */
    public function prePersist(LifecycleEventArgs $event): void
    {
        $target = $event->getObject();

        if ($target instanceof CreatedDateProviderInterface) {
            $reflection = new \ReflectionProperty($target, 'createdAt');

            $reflection->setValue($target, $this->clock->now());
        }
    }
}
