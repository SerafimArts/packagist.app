<?php

declare(strict_types=1);

namespace Local\HttpData;

use Local\HttpData\Listener\ResponseTransformerListener;
use Local\HttpData\Subscriber\ValidateMissingPropertiesSubscriber;
use Local\HttpData\ValueResolver\BodyDTOResolver;
use Local\HttpData\ValueResolver\QueryDTOResolver;
use Local\HttpData\ValueResolver\ValidatorAwareDTOResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class HttpDataBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        $this->registerQueryValueResolver($container);
        $this->registerBodyValueResolver($container);
        $this->registerResponseDataTransformer($container);
        $this->registerSerializerSubscribers($container);
    }

    private function registerSerializerSubscribers(ContainerBuilder $container): void
    {
        $container->register(ValidateMissingPropertiesSubscriber::class)
            ->addTag('jms_serializer.event_subscriber');
    }

    private function registerResponseDataTransformer(ContainerBuilder $container): void
    {
        $container->register(ResponseTransformerListener::class)
            ->addArgument(new Reference('jms_serializer'))
            ->addTag('kernel.event_listener', [
                'priority' => ResponseTransformerListener::LISTENER_PRIORITY,
            ]);
    }

    private function registerQueryValueResolver(ContainerBuilder $container): void
    {
        $container->register(QueryDTOResolver::class)
            ->addArgument(new Reference('jms_serializer'));

        $container->register('app.request.query_dto_resolve')
            ->setClass(ValidatorAwareDTOResolver::class)
            ->setArgument('$validator', new Reference(ValidatorInterface::class))
            ->setArgument('$resolver', new Reference(QueryDTOResolver::class))
            ->addTag('controller.argument_value_resolver');
    }

    private function registerBodyValueResolver(ContainerBuilder $container): void
    {
        $container->register(BodyDTOResolver::class)
            ->addArgument(new Reference('jms_serializer'));

        $container->register('app.request.body_dto_resolver')
            ->setClass(ValidatorAwareDTOResolver::class)
            ->setArgument('$validator', new Reference(ValidatorInterface::class))
            ->setArgument('$resolver', new Reference(BodyDTOResolver::class))
            ->addTag('controller.argument_value_resolver');
    }
}
