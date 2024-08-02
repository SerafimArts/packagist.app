<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Listener;

use App\Shared\Infrastructure\Transformer\TransformerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

#[AsEventListener(priority: 50)]
final readonly class SuccessfulResponseListener
{
    /**
     * @param TransformerInterface<mixed, mixed> $transformer
     */
    public function __construct(
        private TransformerInterface $transformer,
    ) {}

    public function __invoke(ViewEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $result = $event->getControllerResult();

        if ($result instanceof Response) {
            return;
        }

        $event->setControllerResult($this->transformer->transform(
            entry: $result,
        ));
    }
}
