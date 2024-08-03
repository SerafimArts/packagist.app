<?php

declare(strict_types=1);

namespace App\Shared\Domain\Id;

use App\Shared\Domain\ValueObjectInterface;
use Psr\Clock\ClockInterface;
use Random\Randomizer;

/**
 * @phpstan-consistent-constructor
 */
abstract readonly class ObjectId implements IdInterface
{
    /**
     * @var non-empty-string
     */
    private const string PATTERN = '/^[a-f\d]{24}$/';

    /**
     * @var non-empty-string
     */
    private string $value;

    /**
     * @param non-empty-string|\Stringable $value
     *
     * @psalm-suppress PropertyTypeCoercion
     */
    public function __construct(string|\Stringable $value)
    {
        $value = (string) $value;

        assert(\preg_match(self::PATTERN, $value) !== false);

        /** @var non-empty-string $value */
        $this->value = $value;
    }

    public static function new(
        ?ClockInterface $clock = null,
        ?Randomizer $randomizer = null
    ): static {
        $now = $clock?->now() ?? new \DateTimeImmutable();
        $suffix = ($randomizer ?? new Randomizer())->getBytes(8);

        return new static(
            value: \dechex($now->getTimestamp())
                . \bin2hex($suffix),
        );
    }

    /**
     * @param array<array-key, mixed> $data
     * @param non-empty-string $key
     *
     * @throws \JsonException
     */
    public static function fromArray(array $data, string $key = '_id'): static
    {
        if (!\is_array($data[$key] ?? null)) {
            throw new \InvalidArgumentException(\vsprintf('Invalid data value %s for %s ID', [
                \json_encode($data, flags: \JSON_THROW_ON_ERROR),
                static::class,
            ]));
        }

        $id = $data[$key]['$oid'] ?? null;

        if (\is_string($id) && $id !== '') {
            return new static($id);
        }

        throw new \InvalidArgumentException('An "$oid" field required');
    }

    /**
     * @param non-empty-string $value
     */
    public static function fromBinaryString(string $value): static
    {
        return new static(\bin2hex($value));
    }

    /**
     * @api
     *
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * @api
     *
     * @return non-empty-string
     */
    public function toBinaryString(): string
    {
        /** @var non-empty-string */
        return \hex2bin($this->toString());
    }

    /**
     * Returns the timestamp component of this ObjectId
     */
    public function getTimestamp(): int
    {
        $prefix = \substr($this->value, 0, -16);

        return (int) \hexdec($prefix);
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $this === $object
            || ($object instanceof static && $this->value === (string) $object);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
