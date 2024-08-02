<?php

declare(strict_types=1);

namespace Local\Token\Tests\Driver;

use Local\Token\Algo;
use Local\Token\Exception\TokenValidationException;
use Local\Token\Tests\TestCase;
use Local\Token\TokenBuilderInterface;
use Local\Token\TokenParserInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group('local/token')]
class SignatureTest extends TestCase
{
    protected static function getParsersWithUnknownKey(): iterable
    {
        $key = self::key('unknown.rs256.pub');

        yield from self::getParsers($key, Algo::RS256);
    }

    public static function driversDataProviderWithUnknownParsers(): iterable
    {
        foreach (self::getKeyPairs() as $name => [$algo, $public, $private]) {
            foreach (self::getParsersWithUnknownKey() as $parserName => $parser) {
                foreach (self::getBuilders($private, $algo) as $builderName => $builder) {
                    yield "[$name] $parserName parser + $builderName builder" => [$parser, $builder];
                }
            }
        }
    }

    #[DataProvider('driversDataProviderWithUnknownParsers')]
    public function testSignatureValidation(TokenParserInterface $parser, TokenBuilderInterface $builder): void
    {
        $token = $builder->build(['test' => 0xDEAD_BEEF]);

        self::expectException(TokenValidationException::class);

        $parser->parse($token);
    }
}
