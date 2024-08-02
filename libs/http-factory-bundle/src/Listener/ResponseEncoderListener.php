<?php

declare(strict_types=1);

namespace Local\HttpFactory\Listener;

use Local\HttpFactory\ResponseEncoderFactoryInterface;
use Local\HttpFactory\ResponseEncoderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

/**
 * Converts non-response payloads to response payload string.
 */
final readonly class ResponseEncoderListener
{
    public const int LISTENER_PRIORITY = 30;

    public function __construct(
        private ResponseEncoderFactoryInterface $encoders,
        private ResponseEncoderInterface $default,
    ) {}

    private function isSerializableResponse(mixed $result): bool
    {
        return !$result instanceof Response;
    }

    public function __invoke(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        // Skip non-serializable responses.
        if (!$this->isSerializableResponse($result)) {
            return;
        }

        // Select suitable encoder from factory.
        $encoder = $this->encoders->createEncoder($event->getRequest())
            ?? $this->default;

        $encoded = $encoder->encode($result, Response::HTTP_OK);

        // Update response content
        $event->setResponse($encoded);
    }
}
