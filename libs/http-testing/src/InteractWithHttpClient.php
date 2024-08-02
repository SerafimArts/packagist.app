<?php

declare(strict_types=1);

namespace Local\Testing\Http;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Before;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

/**
 * @mixin WebTestCase
 */
trait InteractWithHttpClient
{
    protected KernelBrowser $http;

    #[Before]
    protected function setUpInteractWithHttpClient(): void
    {
        try {
            $this->http = self::createClient();
        } catch (\LogicException $e) {
            $message = $e->getMessage();

            if (\str_contains($message, 'cannot create the client')) {
                Assert::markTestIncomplete($message);
            }

            throw $e;
        }

        self::getClient($this->http);
    }

    /**
     * @param array<non-empty-string, string> $headers
     *
     * @return array<non-empty-string, string>
     */
    protected function headersToServer(array $headers): array
    {
        $server = [];

        foreach ($headers as $name => $value) {
            // Format header name to server-compatible format
            $name = \str_replace('-', '_', $name);
            $name = 'HTTP_' . \strtoupper($name);

            // Add header aliases
            if ($name === 'HTTP_CONTENT_TYPE') {
                $server['CONTENT_TYPE'] = $value;
            }

            $server[$name] = $value;
        }

        return $server;
    }

    /**
     * @param Request::METHOD_*|non-empty-string $method
     * @param non-empty-string $uri
     * @param array<non-empty-string, string> $headers
     * @param non-empty-string|array<non-empty-string, mixed>|null $body
     */
    protected function request(string $method, string $uri, string|array|null $body = null, array $headers = []): TestingResponse
    {
        [$parameters, $content] = \is_array($body)
            ? [$body, null]
            : [[], $body];

        $this->http->request($method, $uri, $parameters, [], $this->headersToServer($headers), $content);

        return new TestingResponse($this->http->getResponse());
    }

    /**
     * @param Request::METHOD_*|non-empty-string $method
     * @param non-empty-string $uri
     * @param array<non-empty-string, string> $headers
     *
     * @throws \JsonException
     */
    protected function json(string $method, string $uri, ?array $body = null, array $headers = []): TestingResponse
    {
        $headers = \array_merge($headers, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        $content = \json_encode($body ?? null, \JSON_THROW_ON_ERROR);

        return $this->request($method, $uri, $content, $headers);
    }

    abstract protected static function getClient(?AbstractBrowser $newClient = null): ?AbstractBrowser;

    /**
     * Creates a KernelBrowser.
     *
     * @param array $options An array of options to pass to the createKernel method
     * @param array $server An array of server parameters
     */
    abstract protected static function createClient(
        array $options = [],
        array $server = []
    ): KernelBrowser;
}
