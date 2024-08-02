<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Local\HttpFactory\Driver\JsonDriver;
use Local\HttpFactory\Driver\MessagePackDriver;
use Local\HttpFactory\Driver\YamlDriver;
use Local\HttpFactory\Listener\RequestDecoderListener;
use Local\HttpFactory\Listener\ResponseEncoderListener;
use MessagePack\MessagePack;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Yaml\Yaml;

final class HttpFactoryBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        $this->registerRequestListener($container);
        $this->registerResponseListener($container);
        $this->registerBuiltinDrivers($container);
        $this->registerFactories($container);
    }

    private function registerRequestListener(ContainerBuilder $container): void
    {
        $container->register(RequestDecoderListener::class)
            ->addArgument(new Reference(RequestDecoderFactoryInterface::class))
            ->addTag('kernel.event_listener', [
                'priority' => RequestDecoderListener::LISTENER_PRIORITY,
            ]);
    }

    private function registerResponseListener(ContainerBuilder $container): void
    {
        $container->register(ResponseEncoderListener::class)
            ->addArgument(new Reference(ResponseEncoderFactoryInterface::class))
            ->addArgument(new Reference(JsonDriver::class))
            ->addTag('kernel.event_listener', [
                'priority' => ResponseEncoderListener::LISTENER_PRIORITY,
            ]);
    }

    private function registerBuiltinDrivers(ContainerBuilder $container): void
    {
        if (\class_exists(MessagePack::class)) {
            $container->register(MessagePackDriver::class)
                ->addTag('app.http.decoder')
                ->addTag('app.http.encoder');
        }

        if (\class_exists(Yaml::class)) {
            $container->register(YamlDriver::class)
                ->addTag('app.http.decoder')
                ->addTag('app.http.encoder');
        }

        $container->register(JsonDriver::class)
            ->setArgument('$debug', '%kernel.debug%')
            ->addTag('app.http.decoder')
            ->addTag('app.http.encoder');
    }

    private function registerFactories(ContainerBuilder $container): void
    {
        $container->register(RequestDecoderFactoryInterface::class, RequestDecoderFactory::class)
            ->setArgument('$decoders', new AutowireIterator('app.http.decoder'));

        $container->register(ResponseEncoderFactoryInterface::class, ResponseEncoderFactory::class)
            ->setArgument('$encoders', new AutowireIterator('app.http.encoder'));
    }
}
