<?php

declare(strict_types=1);

namespace Local\Testing\Http\Extension;

use Local\Testing\Http\Constraint\JsonMatches;
use Local\Testing\Http\TestingResponse;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsFalse;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\Constraint\StringContains;

/**
 * @mixin TestingResponse
 */
trait JsonPathExtension
{
    /**
     * @param non-empty-string $path
     */
    public function assertJsonPath(string $path, Constraint $constraint, string $message = ''): self
    {
        $content = $this->response->getContent();

        Assert::assertThat($content, new JsonMatches($path, $constraint), $message);

        return $this;
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathAll(string $path, Constraint $constraint, string $message = ''): self
    {
        $content = $this->response->getContent();

        Assert::assertThat($content, new JsonMatches($path, $constraint, true), $message);

        return $this;
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathMatches(string $path, string $regex, string $message = ''): self
    {
        return $this->assertJsonPath($path, new RegularExpression($regex), $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathEqual(string $path, mixed $value, string $message = ''): self
    {
        return $this->assertJsonPath($path, new IsEqual($value), $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathSame(string $path, mixed $value, string $message = ''): self
    {
        return $this->assertJsonPath($path, new IsIdentical($value), $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathNotSame(string $path, mixed $value, string $message = ''): self
    {
        $expr = new LogicalNot(new IsIdentical($value));

        return $this->assertJsonPath($path, $expr, $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathStringContains(string $path, string $value, string $message = ''): self
    {
        return $this->assertJsonPath($path, new StringContains($value), $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathIsNull(string $path, string $message = ''): self
    {
        return $this->assertJsonPath($path, new IsNull(), $message);
    }

    /**
     * @param non-empty-string $path
     * @param int<0, max> $size
     */
    public function assertJsonPathIsArrayOfSize(string $path, int $size, string $message = ''): self
    {
        return $this->assertJsonPath($path, new Count($size), $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathTrue(string $path, string $message = ''): self
    {
        return $this->assertJsonPath($path, new IsTrue(), $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathFalse(string $path, string $message = ''): self
    {
        return $this->assertJsonPath($path, new IsFalse(), $message);
    }
}
