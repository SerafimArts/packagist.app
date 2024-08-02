<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Local\Testing\Http\InteractWithHttpClient;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class TestCase extends WebTestCase
{
    use InteractWithHttpClient;

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
