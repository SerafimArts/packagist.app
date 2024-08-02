<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

/**
 * @api
 */
#[AsEventListener(priority: 50)]
final readonly class SuccessfulResponseListener extends ResponseListener
{
    public function __invoke(ViewEvent $event): void
    {
        $transformer = $this->getTransformer($event);

        if ($transformer === null) {
            return;
        }

        $result = $event->getControllerResult();

        if ($result instanceof Response) {
            return;
        }

        $event->setControllerResult($transformer->transform($result));
    }
}
