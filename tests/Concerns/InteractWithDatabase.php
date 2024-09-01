<?php

declare(strict_types=1);

namespace App\Tests\Concerns;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @mixin KernelTestCase
 */
trait InteractWithDatabase
{
    use InteractWithContainer;

    /**
     * @template T of object
     * @param T $entity
     * @return T
     */
    protected function given(object $entity): object
    {
        $em = $this->get(EntityManagerInterface::class);

        $em->persist($entity);
        $em->flush();
        $em->clear();

        return $entity;
    }
}
