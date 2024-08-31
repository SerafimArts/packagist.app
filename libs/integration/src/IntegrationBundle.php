<?php

declare(strict_types=1);

namespace Local\Integration;

use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal Local\Integration
 */
final class IntegrationBundle extends Bundle
{
    /**
     * @var non-empty-string
     */
    public const string CLIENT_TAG_NAME = 'app.integration.client';

    /**
     * @var non-empty-string
     */
    public const string CLIENT_TAG_KEY = 'key';

    public function build(ContainerBuilder $container): void
    {
        $container->register(FactoryInterface::class)
            ->setClass(Factory::class)
            ->setArgument('$container', new ServiceLocatorArgument(
                new TaggedIteratorArgument(
                    tag: self::CLIENT_TAG_NAME,
                    indexAttribute: self::CLIENT_TAG_KEY,
                ),
            ));
    }
}
