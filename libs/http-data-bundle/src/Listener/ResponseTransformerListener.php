<?php

declare(strict_types=1);

namespace Local\HttpData\Listener;

use JMS\Serializer\ArrayTransformerInterface;
use Local\HttpFactory\Listener\ResponseEncoderListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

/**
 * Transform variant DTO to array.
 */
final readonly class ResponseTransformerListener
{
    /**
     * Must be executed BEFORE [data to string] encoder.
     */
    public const int LISTENER_PRIORITY = ResponseEncoderListener::LISTENER_PRIORITY + 10;

    public function __construct(
        protected ArrayTransformerInterface $serializer,
    ) {}

    private function isTransformableResponse(mixed $result): bool
    {
        return !$result instanceof Response;
    }

    public function __invoke(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        // Skip non transformable responses.
        if (!$this->isTransformableResponse($result)) {
            return;
        }

        $transformed = $this->serializer->toArray($result);

        $event->setControllerResult((object) $transformed);
    }
}
