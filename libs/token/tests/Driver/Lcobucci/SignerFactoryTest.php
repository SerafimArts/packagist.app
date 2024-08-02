<?php

declare(strict_types=1);

namespace Local\Token\Tests\Driver\Lcobucci;

use Lcobucci\JWT\Signer;
use Local\Token\Algo;
use Local\Token\AlgoInterface;
use Local\Token\Driver\Lcobucci\SignerFactory;
use Local\Token\Tests\Driver\DriverTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group('local/token')]
final class SignerFactoryTest extends DriverTestCase
{
    public static function algosDataProvider(): iterable
    {
        $reflection = (new \ReflectionClass(Algo::class))
            ->getReflectionConstants();

        foreach ($reflection as $constant) {
            yield $constant->getName() => [$constant->getValue()];
        }
    }

    #[DataProvider('algosDataProvider')]
    public function testAlgoIsCreatable(AlgoInterface $algo): void
    {
        $factory = new SignerFactory();

        self::assertInstanceOf(Signer::class, $factory->create($algo));
    }

    #[DataProvider('algosDataProvider')]
    public function testAlgoIsCreatableOverCustomInterface(AlgoInterface $algo): void
    {
        $factory = new SignerFactory();

        $mapped = new class ($algo) implements AlgoInterface {
            public function __construct(
                private readonly AlgoInterface $algo,
            ) {}

            public function getName(): string
            {
                return $this->algo->getName();
            }
        };

        self::assertInstanceOf(Signer::class, $factory->create($mapped));
    }

    #[DataProvider('algosDataProvider')]
    public function testAlgoIsNotCreatable(AlgoInterface $algo): void
    {
        $factory = new SignerFactory();

        $this->expectException(\InvalidArgumentException::class);

        $factory->create(new class ($algo) implements AlgoInterface {
            public function __construct(
                private readonly AlgoInterface $algo,
            ) {}

            public function getName(): string
            {
                return 'INVALID_' . $this->algo->getName();
            }
        });
    }
}
