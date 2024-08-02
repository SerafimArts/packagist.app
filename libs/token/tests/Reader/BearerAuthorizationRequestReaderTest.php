<?php

declare(strict_types=1);

namespace Local\Token\Tests\Reader;

use Local\Token\Reader\SymfonyRequest\AuthorizationHeaderSymfonyRequestReader;
use Local\Token\Reader\SymfonyRequest\BearerAuthorizationHeaderSymfonyRequestReader;
use Local\Token\Reader\TokenReaderInterface;
use PHPUnit\Framework\Attributes\Group;

#[Group('local/token')]
final class BearerAuthorizationRequestReaderTest extends ReaderTestCase
{
    public static function positiveRequestsDataProvider(): iterable
    {
        yield 'bearer token' => [self::requestWithHeaders([
            'Authorization' => 'bearer token',
        ]), 'token'];

        yield 'trimming bearer value' => [self::requestWithHeaders([
            'Authorization' => 'bearer   token     ',
        ]), 'token'];

        yield 'upper case bearer prefix' => [self::requestWithHeaders([
            'authorization' => 'BEARER token',
        ]), 'token'];

        yield 'several cases bearer prefix' => [self::requestWithHeaders([
            'authorization' => 'BeArEr token',
        ]), 'token'];
    }

    public static function negativeRequestsDataProvider(): iterable
    {
        yield 'lower case' => [self::requestWithHeaders([
            'authorization' => 'token',
        ]), 'token'];

        yield 'upper case' => [self::requestWithHeaders([
            'AUTHORIZATION' => 'token',
        ]), 'token'];

        yield 'several cases' => [self::requestWithHeaders([
            'AuThOrIzAtIoN' => 'token',
        ]), 'token'];

        yield 'trimming value' => [self::requestWithHeaders([
            'Authorization' => '  token  ',
        ]), 'token'];

        yield 'no header' => [self::requestWithHeaders()];

        yield 'name with WS' => [self::requestWithHeaders([
            'authorization ' => 'token',
        ])];

        yield 'no whitespace after "bearer" prefix' => [self::requestWithHeaders([
            'authorization' => 'bearertoken',
        ]), 'bearertoken'];
    }

    protected function getReader(): TokenReaderInterface
    {
        return new BearerAuthorizationHeaderSymfonyRequestReader(
            new AuthorizationHeaderSymfonyRequestReader(),
        );
    }
}
