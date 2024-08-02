<?php

declare(strict_types=1);

namespace Local\Token\Tests\Reader;

use Local\Token\Reader\TokenReaderInterface;
use Local\Token\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Request;

#[Group('local/token')]
abstract class ReaderTestCase extends TestCase
{
    /**
     * @param array<non-empty-string, string> $headers
     */
    protected static function requestWithHeaders(array $headers = []): Request
    {
        $request = Request::create('/');
        $request->headers->add($headers);

        return $request;
    }

    /**
     * @return iterable<array-key, array{Request, string}>
     */
    abstract public static function positiveRequestsDataProvider(): iterable;

    /**
     * @return iterable<array-key, array{Request}>
     */
    abstract public static function negativeRequestsDataProvider(): iterable;

    abstract protected function getReader(): TokenReaderInterface;

    #[DataProvider('positiveRequestsDataProvider')]
    public function testIsReadable(Request $request): void
    {
        $reader = $this->getReader();

        self::assertTrue($reader->isReadable($request));
    }

    #[DataProvider('positiveRequestsDataProvider')]
    public function testTryRead(Request $request, string $token): void
    {
        $reader = $this->getReader();

        self::assertSame($token, $reader->read($request));
    }

    #[DataProvider('negativeRequestsDataProvider')]
    public function testIsNotReadable(Request $request): void
    {
        $reader = $this->getReader();

        self::assertFalse($reader->isReadable($request));
    }

    #[DataProvider('negativeRequestsDataProvider')]
    public function testReadingReturnEmptyString(Request $request): void
    {
        $reader = $this->getReader();

        self::assertSame('', $reader->read($request));
    }
}
