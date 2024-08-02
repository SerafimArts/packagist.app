<?php

declare(strict_types=1);

namespace Local\Testing\Http\Extension;

use Local\Testing\Http\TestingResponse;
use PHPUnit\Framework\Assert;

/**
 * @mixin TestingResponse
 */
trait HeadersExtension
{
    public function assertHeaderSame(string $name, mixed $value, string $message = ''): self
    {
        $this->assertHeaderExists($name);

        $actual = $this->response->headers->get($name);

        if ($message === '') {
            $message = \vsprintf('Header [%s] was found, but value %s does not match %s', [
                $name,
                \var_export($actual, true),
                \var_export($value, true),
            ]);
        }

        Assert::assertSame($actual, $value, $message);

        return $this;
    }

    public function assertHeaderExists(string $name, string $message = ''): self
    {
        $message = $message ?: \sprintf('Header [%s] not present on response', $name);

        Assert::assertTrue($this->response->headers->has($name), $message);

        return $this;
    }

    public function assertHeaderNotSame(string $name, mixed $value, string $message = ''): self
    {
        $this->assertHeaderExists($name);

        $actual = $this->response->headers->get($name);

        if ($message !== '') {
            $message = \vsprintf('Header [%s] was found, but value %s is same that %s', [
                $name,
                \var_export($actual, true),
                \var_export($value, true),
            ]);
        }

        Assert::assertNotSame($actual, $message);

        return $this;
    }

    public function assertHeaderNotExists(string $name, string $message = ''): self
    {
        if ($message === '') {
            $message = \sprintf('Unexpected header [%s] is present on response', $name);
        }

        Assert::assertFalse($this->response->headers->has($name), $message);

        return $this;
    }
}
