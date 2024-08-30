<?php

declare(strict_types=1);

namespace App\Tests\Concerns;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * @mixin KernelTestCase
 */
trait InteractWithContainer
{
    abstract protected static function getContainer(): Container;

    /**
     * @template T of object
     * @param class-string<T> $service
     * @return T
     */
    protected function get(string $service): object
    {
        $container = $this->getContainer();

        /** @var T */
        return $container->get($service);
    }
}
