<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Listener;

use App\Shared\Application\Exception\ApplicationException;
use App\Shared\Domain\Exception\DomainException;
use App\Shared\Infrastructure\Transformer\TransformerInterface;
use App\Shared\Presentation\Exception\PresentationException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Validator\Exception\ExceptionInterface as ValidationExceptionInterface;

/**
 * @api
 */
#[AsEventListener(priority: -70)]
final readonly class FailureResponseListener extends ResponseListener
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $transformer = $this->getTransformer($event);

        if ($transformer === null) {
            return;
        }

        $kernel = $event->getKernel();
        $request = $event->getRequest();
        $exception = $event->getThrowable();

        $response = $kernel->handle(
            request: $request->duplicate(null, null, [
                '_controller' => $this->getExceptionHandler($transformer),
                'exception' => $exception,
            ]),
            type: HttpKernelInterface::SUB_REQUEST,
            catch: false,
        );

        $response->setStatusCode($this->getStatusCode($exception));
        $response->headers->add($this->getHeaders($exception));

        $event->setResponse($response);
    }

    /**
     * @return array<non-empty-string, string>
     */
    private function getHeaders(\Throwable $e): array
    {
        return match (true) {
            $e instanceof HttpExceptionInterface => $e->getHeaders(),
            default => [],
        };
    }

    private function getStatusCode(\Throwable $e): int
    {
        return match (true) {
            $e instanceof HttpExceptionInterface => $e->getStatusCode(),
            $e instanceof ValidationExceptionInterface,
            $e instanceof PresentationException,
            $e instanceof ApplicationException,
            $e instanceof DomainException => Response::HTTP_UNPROCESSABLE_ENTITY,
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    /**
     * @param TransformerInterface<\Throwable, mixed> $transformer
     */
    protected function getExceptionHandler(TransformerInterface $transformer): object
    {
        return new readonly class ($transformer) {
            /**
             * @param TransformerInterface<\Throwable, mixed> $transformer
             */
            public function __construct(
                private TransformerInterface $transformer,
            ) {}

            public function __invoke(\Throwable $exception): mixed
            {
                return $this->transformer->transform($exception);
            }
        };
    }
}
