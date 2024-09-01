<?php

declare(strict_types=1);

namespace Local\Testing\Http\Constraint;

use Flow\JSONPath\JSONPath;
use Flow\JSONPath\JSONPathException;
use Local\Testing\Http\Exception\PackageRequiredException;
use PHPUnit\Framework\Constraint\Constraint;

final class JsonMatches extends Constraint
{
    /**
     * @var non-empty-string
     */
    private string $jsonPath;

    private Constraint $constraint;

    private bool $matchAll;

    /**
     * @param non-empty-string $jsonPath The JSON path that identifies the
     *        value(s) in the JSON document that the constraint should match.
     * @param Constraint $constraint The actual constraint that the selected
     *        value(s) must match.
     * @param bool $matchAll This flag controls how this constraint handles
     *        multiple values. Usually, this constraint will match successfully,
     *        when (at least) one found value matches the given constraint. When
     *        this flag is set, _all_ found values must match the constraint.
     */
    public function __construct(
        string $jsonPath,
        Constraint $constraint,
        bool $matchAll = false
    ) {
        $this->matchAll = $matchAll;
        $this->constraint = $constraint;
        $this->jsonPath = $jsonPath;

        if (!\class_exists(JSONPath::class)) {
            throw new PackageRequiredException('softcreatr/jsonpath');
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return non-empty-string
     */
    public function toString(): string
    {
        $constraint = \get_class($this->constraint);

        if (\method_exists($this->constraint, 'toString')) {
            /** @psalm-suppress InternalMethod */
            $constraint = $this->constraint->toString();
        }

        /** @var non-empty-string */
        return \vsprintf('%s at JSON path "%s"', [
            $constraint,
            $this->jsonPath,
        ]);
    }

    /**
     * @throws JSONPathException
     * @throws \JsonException
     */
    protected function matches(mixed $other): bool
    {
        if (\is_string($other)) {
            $other = \json_decode($other, false, 512, \JSON_THROW_ON_ERROR);
        }

        if (!\is_array($other)) {
            $other = (array) $other;
        }

        $result = (new JSONPath($other))->find($this->jsonPath);

        if (!isset($result[0])) {
            return false;
        }

        $combineFunc = $this->buildCombinationFunction();
        $matches = null;

        /** @var mixed $value */
        foreach ($result as $value) {
            if ($value instanceof JSONPath) {
                $value = $value->getData();
            }

            $singleMatchResult = $this->constraint->evaluate($value, '', true);
            $matches = $combineFunc($matches, $singleMatchResult);
        }

        return (bool) $matches;
    }

    /**
     * @return callable(mixed, mixed):bool
     */
    private function buildCombinationFunction(): callable
    {
        if ($this->matchAll) {
            return static fn($first, $second): bool => ($first === null) ? (bool) $second : $first && $second;
        }

        return static fn($first, $second): bool => ($first === null) ? (bool) $second : $first || $second;
    }
}
