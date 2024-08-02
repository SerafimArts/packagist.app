<?php

declare(strict_types=1);

namespace Local\Token\Tests\Driver;

use Local\Token\AlgoInterface;
use Local\Token\Tests\TestCase;
use Local\Token\TokenBuilderInterface;
use Local\Token\TokenParserInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group('local/token')]
class ClaimsTest extends TestCase
{
    #[DataProvider('driversDataProvider')]
    public function testClaimsWithoutExpiration(TokenParserInterface $parser, TokenBuilderInterface $builder, AlgoInterface $algo): void
    {
        $parsed = $parser->parse(
            $builder->build(['test' => 0xDEAD_BEEF])
        );

        self::assertCount(4, $parsed);

        self::assertArrayHasKey('typ', $parsed);
        self::assertSame('JWT', $parsed['typ']);

        self::assertArrayHasKey('alg', $parsed);
        self::assertSame($algo->getName(), $parsed['alg']);

        self::assertArrayHasKey('test', $parsed);
        self::assertSame(0xDEAD_BEEF, $parsed['test']);

        self::assertArrayHasKey('iat', $parsed);
        self::assertInstanceOf(\DateTimeImmutable::class, $parsed['iat']);
    }
}
