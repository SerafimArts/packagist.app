<?php

declare(strict_types=1);

namespace Local\Testing\Http\Extension;

use Local\Testing\Http\Constraint\JsonMatchesSchema;
use Local\Testing\Http\TestingResponse;
use PHPUnit\Framework\Assert;

/**
 * @mixin TestingResponse
 */
trait JsonSchemaExtension
{
    /**
     * @throws \JsonException
     */
    public function assertJsonSchemaMatches(array|string|object $schema, string $message = ''): self
    {
        $content = $this->response->getContent();

        Assert::assertThat($content, new JsonMatchesSchema($schema), $message);

        return $this;
    }

    /**
     * @throws \JsonException
     */
    public function assertJsonSchemaFileMatches(string $file, string $message = ''): self
    {
        $content = $this->response->getContent();

        if (!\is_file($file)) {
            throw new \LogicException('JSON Schema file "' . $file . '" not found');
        }

        $schema = \file_get_contents($file);

        Assert::assertThat($content, new JsonMatchesSchema($schema), $message);

        return $this;
    }
}
