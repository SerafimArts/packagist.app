<?php

declare(strict_types=1);

namespace Local\Token\Tests\Driver;

use Local\Token\Exception\TokenValidationException;
use Local\Token\TokenParserInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group('local/token')]
final class InvalidTokensTest extends DriverTestCase
{
    #[DataProvider('driversDataProvider')]
    public function testDecodingError(TokenParserInterface $parser): void
    {
        self::expectException(TokenValidationException::class);

        $parser->parse('<DEAD_BEEF>');
    }
}
