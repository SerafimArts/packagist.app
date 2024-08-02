<?php

declare(strict_types=1);

namespace Local\Token\Tests\Driver;

use Local\Token\Exception\TokenExpirationException;
use Local\Token\Tests\TestCase;
use Local\Token\TokenBuilderInterface;
use Local\Token\TokenParserInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group('local/token')]
class ExpirationTest extends TestCase
{
    #[DataProvider('driversDataProvider')]
    public function testInfinityExpiration(TokenParserInterface $parser, TokenBuilderInterface $builder): void
    {
        $token = $builder->build(['test' => 'DEAD_BEEF']);
        $parsed = $parser->parse($token);

        self::assertArrayNotHasKey('exp', $parsed);
    }

    #[DataProvider('driversDataProvider')]
    public function testTokenWithExpiration(TokenParserInterface $parser, TokenBuilderInterface $builder): void
    {
        $token = $builder->build(['test' => 'DEAD_BEEF'], 'P1D');
        $parsed = $parser->parse($token);

        self::assertArrayHasKey('exp', $parsed);
    }

    #[DataProvider('driversDataProvider')]
    public function testTokenIsExpired(TokenParserInterface $parser, TokenBuilderInterface $builder): void
    {
        $interval = new \DateInterval('P1D');
        $interval->invert = 1;

        $token = $builder->build(['test' => 'DEAD_BEEF'], $interval);

        self::expectException(TokenExpirationException::class);

        $parsed = $parser->parse($token);

        self::assertArrayHasKey('exp', $parsed);
    }
}
