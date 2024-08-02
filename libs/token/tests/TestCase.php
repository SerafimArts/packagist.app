<?php

declare(strict_types=1);

namespace Local\Token\Tests;

use Local\Token\Algo;
use Local\Token\AlgoInterface;
use Local\Token\Driver\FirebaseTokenBuilder;
use Local\Token\Driver\FirebaseTokenParser;
use Local\Token\Driver\LcobucciTokenBuilder;
use Local\Token\Driver\LcobucciTokenParser;
use Local\Token\TokenBuilderInterface;
use Local\Token\TokenParserInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

#[Group('local/token')]
abstract class TestCase extends BaseTestCase
{
    /**
     * @return non-empty-string
     */
    protected static function key(string $name): string
    {
        return (string) \file_get_contents(__DIR__ . '/Stub/keys/' . $name);
    }

    protected static function getParsers(string $key, AlgoInterface $algo, ?ClockInterface $clock = null): iterable
    {
        $clock ??= new NativeClock();

        if (\class_exists(\Firebase\JWT\JWT::class)) {
            yield 'firebase' => new FirebaseTokenParser($key, $algo, $clock);
        }

        if (\interface_exists(\Lcobucci\JWT\Parser::class)) {
            yield 'lcobucci' => new LcobucciTokenParser($key, $algo, $clock);
        }
    }

    protected static function getBuilders(string $key, AlgoInterface $algo, ?ClockInterface $clock = null): iterable
    {
        $clock ??= new NativeClock();

        if (\class_exists(\Firebase\JWT\JWT::class)) {
            yield 'firebase' => new FirebaseTokenBuilder($key, $algo, $clock);
        }

        if (\interface_exists(\Lcobucci\JWT\Builder::class)) {
            yield 'lcobucci' => new LcobucciTokenBuilder($key, $algo, $clock);
        }
    }

    /**
     * @return iterable<array-key, array{AlgoInterface, non-empty-string, non-empty-string}>
     */
    public static function getKeyPairs(): iterable
    {
        yield Algo::RS256->getName() => [
            Algo::RS256,
            self::key('testing.rs256.pub'),
            self::key('testing.rs256.key'),
        ];

        yield Algo::ES256->getName() => [
            Algo::ES256,
            self::key('testing.ecdsa256.pub'),
            self::key('testing.ecdsa256.key'),
        ];
    }

    /**
     * @return iterable<array-key, array{TokenParserInterface, TokenBuilderInterface}>
     */
    public static function driversDataProvider(): iterable
    {
        yield from self::getDrivers();
    }

    /**
     * @return iterable<array-key, array{TokenParserInterface, TokenBuilderInterface, string}>
     */
    public static function getDrivers(?ClockInterface $clock = null): iterable
    {
        foreach (self::getKeyPairs() as $name => [$algo, $public, $private]) {
            foreach (self::getParsers($public, $algo, $clock) as $parserName => $parser) {
                foreach (self::getBuilders($private, $algo, $clock) as $builderName => $builder) {
                    yield "[$name] $parserName parser + $builderName builder" => [$parser, $builder, $algo];
                }
            }
        }
    }
}
