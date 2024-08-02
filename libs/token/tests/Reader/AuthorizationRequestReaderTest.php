<?php

declare(strict_types=1);

namespace Local\Token\Tests\Reader;

use Local\Token\Reader\SymfonyRequest\AuthorizationHeaderSymfonyRequestReader;
use Local\Token\Reader\TokenReaderInterface;
use PHPUnit\Framework\Attributes\Group;

#[Group('local/token')]
final class AuthorizationRequestReaderTest extends ReaderTestCase
{
    public static function positiveRequestsDataProvider(): iterable
    {
        yield 'lower case' => [self::requestWithHeaders(['authorization' => 'token']), 'token'];
        yield 'upper case' => [self::requestWithHeaders(['AUTHORIZATION' => 'token']), 'token'];
        yield 'several cases' => [self::requestWithHeaders(['AuThOrIzAtIoN' => 'token']), 'token'];
        yield 'value with WS' => [self::requestWithHeaders(['Authorization' => '  token  ']), 'token'];
    }

    public static function negativeRequestsDataProvider(): iterable
    {
        yield 'no header' => [self::requestWithHeaders()];
        yield 'name with WS' => [self::requestWithHeaders(['authorization ' => 'token'])];
    }

    protected function getReader(): TokenReaderInterface
    {
        return new AuthorizationHeaderSymfonyRequestReader();
    }
}
