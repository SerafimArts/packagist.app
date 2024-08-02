<?php

declare(strict_types=1);

namespace Local\HttpFactory\Driver;

use Local\HttpFactory\RequestMatcherInterface;
use Local\HttpFactory\ResponseMatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract readonly class Driver implements RequestMatcherInterface, ResponseMatcherInterface
{
    public function encode(mixed $data, int $code = self::DEFAULT_HTTP_CODE): Response
    {
        $response = new Response($this->toString($data));
        $response->setStatusCode($code);

        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Vary', 'Accept-Encoding');

        if ($this->shouldSetContentType($response, $this->getSupportedContentTypes())) {
            $response->headers->set('Content-Type', $this->getDefaultContentType());
        }

        return $response;
    }

    public function decode(string $payload): object|array
    {
        if ($payload === '') {
            return [];
        }

        return $this->fromString($payload);
    }

    /**
     * @param list<non-empty-string> $acceptable
     */
    private function shouldSetContentType(Response $response, array $acceptable = []): bool
    {
        $actual = $response->headers->get('Content-Type');

        return !\in_array($actual, $acceptable, true);
    }

    public function isAcceptable(Request $request): bool
    {
        return $this->isAvailable()
            && $this->accepts($request, $this->getSupportedContentTypes());
    }

    /**
     * Returns {@see true} in case of HTTP request provides something like
     * "application/json" or "application/vnd.api+json" accept header.
     *
     * @param list<non-empty-string> $acceptable
     */
    final protected function accepts(Request $request, array $acceptable = []): bool
    {
        foreach ($request->getAcceptableContentTypes() as $contentType) {
            if (\in_array($contentType, $acceptable, true)) {
                return true;
            }
        }

        return false;
    }

    public function isProvides(Request $request): bool
    {
        return $this->isAvailable()
            && $this->provides($request, $this->getSupportedContentTypes());
    }

    /**
     * Returns {@see true} in case of HTTP request provides something like
     * "application/json" content-type header.
     *
     * @param list<non-empty-string> $contentTypes
     */
    final protected function provides(Request $request, array $contentTypes = []): bool
    {
        foreach ($request->headers->all('content-type') as $contentType) {
            if (\in_array($contentType, $contentTypes, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns list of the supported content-types for the given driver.
     *
     * @return non-empty-list<non-empty-string>
     */
    abstract protected function getSupportedContentTypes(): array;

    /**
     * @return non-empty-string
     */
    protected function getDefaultContentType(): string
    {
        $contentTypes = $this->getSupportedContentTypes();

        return \reset($contentTypes);
    }

    /**
     * Returns {@see true} in case of given driver is available.
     */
    protected function isAvailable(): bool
    {
        return true;
    }

    /**
     * Transforms variant payload to response body string.
     */
    abstract protected function toString(mixed $data): string;

    /**
     * Transforms request's body string to variant response payload.
     *
     * @return array<array-key, list<mixed>|scalar|object>
     */
    abstract protected function fromString(string $data): array|object;
}
