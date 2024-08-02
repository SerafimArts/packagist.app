<?php

declare(strict_types=1);

namespace Local\Testing\Http;

use Local\Testing\Http\Extension\HeadersExtension;
use Local\Testing\Http\Extension\JsonPathExtension;
use Local\Testing\Http\Extension\JsonSchemaExtension;
use Local\Testing\Http\Extension\StatusCodesExtension;
use Local\Testing\Http\Extension\VarDumperExtension;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\LogicalNot;
use Symfony\Component\HttpFoundation\Response;

/**
 * @mixin Response
 */
class TestingResponse
{
    use HeadersExtension;
    use StatusCodesExtension;
    use VarDumperExtension;
    use JsonSchemaExtension;
    use JsonPathExtension;

    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function assertContentSame(string $content, string $message = ''): self
    {
        return $this->assertContentMatches(new IsIdentical($content), $message);
    }

    public function assertContentMatches(Constraint $constraint, string $message = ''): self
    {
        $content = $this->response->getContent();

        Assert::assertThat($content, $constraint, $message);

        return $this;
    }

    public function assertContentNotSame(string $content, string $message = ''): self
    {
        return $this->assertContentMatches(new LogicalNot(new IsIdentical($content)), $message);
    }

    public function assertContentEquals(string $content, string $message = ''): self
    {
        return $this->assertContentMatches(new IsEqual($content), $message);
    }

    public function assertContentNotEquals(string $content, string $message = ''): self
    {
        return $this->assertContentMatches(new LogicalNot(new IsEqual($content)), $message);
    }

    /**
     * @throws \JsonException
     */
    public function assertJsonContentEquals($json, string $message = ''): self
    {
        return $this->assertJsonContentMatches(new IsEqual($json), $message);
    }

    /**
     * @throws \JsonException
     */
    public function assertJsonContentMatches(Constraint $constraint, string $message = ''): self
    {
        $this->assertContentIsJson();

        $content = $this->response->getContent();

        if ($content === false) {
            Assert::fail('The response content is not readable to determine the JSON format');
        }

        $data = \json_decode($content, true, 512, \JSON_THROW_ON_ERROR);

        Assert::assertThat($data, $constraint, $message);

        return $this;
    }

    public function assertContentIsJson(string $message = ''): self
    {
        $content = $this->response->getContent();

        if ($content === false) {
            Assert::fail('The response content is not readable to determine the JSON format');
        }

        try {
            \json_decode($content, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            Assert::fail($message ?: $e->getMessage());
        }

        return $this;
    }

    /**
     * @throws \JsonException
     */
    public function assertJsonContentNotEquals($json, string $message = ''): self
    {
        return $this->assertJsonContentMatches(new LogicalNot(new IsEqual($json)), $message);
    }

    /**
     * @param non-empty-string $property
     */
    public function __get(string $property): mixed
    {
        return $this->response->{$property};
    }

    /**
     * @param non-empty-string $method
     */
    public function __call(string $method, array $args): mixed
    {
        return $this->response->{$method}(...$args);
    }
}
