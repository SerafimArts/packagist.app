<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Listener;

use App\Shared\Infrastructure\Transformer\TransformerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

abstract readonly class ResponseListener
{
    /**
     * @param TransformerInterface<mixed, mixed> $default
     */
    public function __construct(
        protected ContainerInterface $api,
        protected TransformerInterface $default,
    ) {}

    private function getApiVersion(RequestEvent $event): ?string
    {
        $request = $event->getRequest();

        return $request->attributes->get('_api');
    }

    /**
     * @return TransformerInterface<mixed, mixed>|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getTransformer(RequestEvent $event): ?TransformerInterface
    {
        if (!$event->isMainRequest()) {
            return null;
        }

        $version = $this->getApiVersion($event);

        if ($version !== null && $this->api->has($version)) {
            return $this->api->get($version);
        }

        return $this->default;
    }
}
