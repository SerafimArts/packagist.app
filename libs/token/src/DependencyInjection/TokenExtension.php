<?php

declare(strict_types=1);

namespace Local\Token\DependencyInjection;

use Local\Token\Reader\SymfonyRequest\AuthorizationHeaderSymfonyRequestReader;
use Local\Token\Reader\SymfonyRequest\BearerAuthorizationHeaderSymfonyRequestReader;
use Local\Token\TokenBuilderFactory;
use Local\Token\TokenBuilderFactoryInterface;
use Local\Token\TokenParserFactory;
use Local\Token\TokenParserFactoryInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Local\Token
 */
final class TokenExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->registerClock($container);
        $this->registerFactories($container);
        $this->registerReaders($container);
    }

    private function registerClock(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(\DateTimeZone::class)) {
            $definition = (new Definition(\DateTimeZone::class))
                ->setArgument('$timezone', 'UTC');

            $container->setDefinition(\DateTimeZone::class, $definition);
        }

        if (!$container->hasDefinition(ClockInterface::class)) {
            $definition = (new Definition(NativeClock::class))
                ->setArgument('$timezone', new Reference(\DateTimeZone::class));

            $container->setDefinition(ClockInterface::class, $definition);
        }
    }

    private function registerFactories(ContainerBuilder $container): void
    {
        $container->register(TokenBuilderFactoryInterface::class, TokenBuilderFactory::class)
            ->setArgument('$clock', new Reference(ClockInterface::class));

        $container->register(TokenParserFactoryInterface::class, TokenParserFactory::class)
            ->setArgument('$clock', new Reference(ClockInterface::class));
    }

    private function registerReaders(ContainerBuilder $container): void
    {
        foreach ($this->getReaderDefinitions() as $definition) {
            $class = $definition->getClass();

            assert($class !== null);

            $container->setDefinition($class, $definition);
        }
    }

    /**
     * @return iterable<Definition>
     */
    private function getReaderDefinitions(): iterable
    {
        yield new Definition(AuthorizationHeaderSymfonyRequestReader::class);

        yield (new Definition(BearerAuthorizationHeaderSymfonyRequestReader::class))
            ->setArgument('$delegate', new Reference(AuthorizationHeaderSymfonyRequestReader::class));
    }
}
