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
use PHPUnit\Framework\Constraint\IsType;
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
     * @param non-empty-string|IsType::TYPE_* $type
     */
    public function assertJsonPathIsType(string $path, string $type, string $message = ''): self
    {
        return $this->assertJsonPath($path, new IsType($type), $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathIsArray(string $path, string $message = ''): self
    {
        return $this->assertJsonPathIsType($path, IsType::TYPE_ARRAY, $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathIsBool(string $path, string $message = ''): self
    {
        return $this->assertJsonPathIsType($path, IsType::TYPE_BOOL, $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathIsFloat(string $path, string $message = ''): self
    {
        return $this->assertJsonPathIsType($path, IsType::TYPE_FLOAT, $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathIsInt(string $path, string $message = ''): self
    {
        return $this->assertJsonPathIsType($path, IsType::TYPE_INT, $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathIsNumeric(string $path, string $message = ''): self
    {
        return $this->assertJsonPathIsType($path, IsType::TYPE_NUMERIC, $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathIsObject(string $path, string $message = ''): self
    {
        return $this->assertJsonPathIsType($path, IsType::TYPE_OBJECT, $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathIsString(string $path, string $message = ''): self
    {
        return $this->assertJsonPathIsType($path, IsType::TYPE_STRING, $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathIsScalar(string $path, string $message = ''): self
    {
        return $this->assertJsonPathIsType($path, IsType::TYPE_SCALAR, $message);
    }

    /**
     * @param non-empty-string $path
     */
    public function assertJsonPathIsIterable(string $path, string $message = ''): self
    {
        return $this->assertJsonPathIsType($path, IsType::TYPE_ITERABLE, $message);
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
