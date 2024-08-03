<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Exception\Http;

use App\Shared\Presentation\Exception\PresentationException;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * @template TData of mixed
 * @template-extends PresentationException<TData>
 *
 * @phpstan-consistent-constructor
 */
class HttpPresentationException extends PresentationException implements
    HttpStatusCodeProviderInterface,
    HttpHeadersProviderInterface,
    HttpExceptionInterface
{
    /**
     * @var int<100, 599>
     */
    private int $httpStatusCode = Response::HTTP_UNPROCESSABLE_ENTITY;

    public readonly HeaderBag $headers;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        $this->headers = new HeaderBag();

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param int<100, 599> $code
     *
     * @return $this
     */
    public function setHttpStatusCode(int $code): self
    {
        $this->httpStatusCode = $code;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getHeaders(): array
    {
        return \iterator_to_array($this->getHttpHeaders(), true);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getHttpHeaders(): iterable
    {
        foreach ($this->headers as $header => $lines) {
            if (!\is_string($header) || $header === '') {
                continue;
            }

            foreach ($lines as $line) {
                if (!\is_string($line)) {
                    continue;
                }

                yield $header => $line;
            }
        }
    }
}
