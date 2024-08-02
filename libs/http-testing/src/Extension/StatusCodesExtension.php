<?php

declare(strict_types=1);

namespace Local\Testing\Http\Extension;

use Local\Testing\Http\TestingResponse;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;

/**
 * @mixin TestingResponse
 */
trait StatusCodesExtension
{
    public function assertStatusOk(string $message = ''): self
    {
        return $this->assertStatus(Response::HTTP_OK, $message);
    }

    public function assertStatus(int $code, string $message = ''): self
    {
        $actual = $this->getStatusCode();

        $message = $message ?: $this->statusMessageWithDetails($code, $actual);

        Assert::assertSame($code, $actual, $message);

        return $this;
    }

    protected function statusMessageWithDetails(int|string $expected, int $actual): string
    {
        $content = $this->getContent();

        return \vsprintf('Expected status-code [%s] but received %s in response %s', [
            $expected,
            $actual,
            $content === false ? '<unkwnown>' : $content,
        ]);
    }

    public function assertStatusBadRequest(string $message = ''): self
    {
        return $this->assertStatus(Response::HTTP_BAD_REQUEST, $message);
    }

    public function assertStatusUnprocessable(string $message = ''): self
    {
        return $this->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY, $message);
    }

    public function assertStatusIn(int $from, int $to, string $message = ''): self
    {
        $message = $message ?: $this->statusMessageWithDetails(
            \sprintf('>=%d, <=%d', $from, $to),
            $this->getStatusCode(),
        );

        Assert::assertTrue($this->isStatusOneOf($from, $to), $message);

        return $this;
    }

    public function isStatusOneOf(int $code, int ...$other): bool
    {
        $current = $this->response->getStatusCode();

        $codes = \array_merge([$code], $other);

        return \in_array($current, $codes, true);
    }

    /**
     * @param non-empty-list<int> $codes
     */
    public function assertStatusOneOf(array $codes, string $message = ''): self
    {
        $message = $message ?: $this->statusMessageWithDetails(
            \implode(', ', $codes),
            $this->getStatusCode(),
        );

        Assert::assertTrue($this->isStatusOneOf(...$codes), $message);

        return $this;
    }

    public function assertSuccessful(string $message = ''): self
    {
        $message = $message ?: $this->statusMessageWithDetails(
            '>=200, <300',
            $this->getStatusCode(),
        );

        Assert::assertTrue($this->isSuccessful(), $message);

        return $this;
    }

    public function isSuccessful(): bool
    {
        return $this->isStatusInRange(200, 299);
    }

    public function isStatusInRange(int $from, int $to): bool
    {
        $current = $this->response->getStatusCode();

        return $current >= $from && $current <= $to;
    }

    public function assertClientError(string $message = ''): self
    {
        $message = $message ?: $this->statusMessageWithDetails(
            '>=400, <500',
            $this->getStatusCode(),
        );

        Assert::assertTrue($this->isClientError(), $message);

        return $this;
    }

    public function isClientError(): bool
    {
        return $this->isStatusInRange(400, 499);
    }

    public function assertServerError(string $message = ''): self
    {
        $message = $message ?: $this->statusMessageWithDetails(
            '>=500, <600',
            $this->getStatusCode(),
        );

        Assert::assertTrue($this->isServerError(), $message);

        return $this;
    }

    public function isServerError(): bool
    {
        return $this->isStatusInRange(500, 599);
    }

    public function assertRedirect(string $message = ''): self
    {
        $message = $message ?: $this->statusMessageWithDetails(
            '201, 301, 302, 303, 307, 308',
            $this->getStatusCode(),
        );

        Assert::assertTrue($this->isRedirect(), $message);

        return $this;
    }

    public function isRedirect(): bool
    {
        return $this->isStatusOneOf(201, 301, 302, 303, 307, 308);
    }
}
