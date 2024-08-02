<?php

declare(strict_types=1);

namespace Local\HttpFactory\Listener;

use Local\HttpFactory\RequestDecoderFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Converts request payload to a PHP raw-array data and stores
 * it in the "decoded_data" request attribute.
 */
final readonly class RequestDecoderListener
{
    /**
     * @var non-empty-string
     */
    public const string ATTR_DECODED_DATA = 'app.decoded_data';

    /**
     * Срабатывает после роутинга у которого приоритет 32.
     */
    public const int LISTENER_PRIORITY = 30;

    public function __construct(
        private RequestDecoderFactoryInterface $decoders,
    ) {}

    private function isContainDecodableBody(Request $request): bool
    {
        return $request->headers->has('content-length')
            || $request->headers->has('content-type')
            || $request->headers->has('transfer-encoding');
    }

    public function __invoke(RequestEvent $event): void
    {
        // Skip non user-created requests.
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        // In case of request does not contain decodable body.
        if (!$this->isContainDecodableBody($request)) {
            return;
        }

        $decoder = $this->decoders->createDecoder($request);

        // In case of factory does not contain suitable driver.
        if ($decoder === null) {
            return;
        }

        try {
            // Try to decode request or fail
            $data = $decoder->decode($request->getContent() ?: '{}');

            $request->attributes->set(self::ATTR_DECODED_DATA, $data);
        } catch (HttpException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new BadRequestHttpException($e->getMessage(), previous: $e);
        }
    }
}
