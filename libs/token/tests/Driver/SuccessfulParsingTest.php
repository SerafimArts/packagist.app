<?php

declare(strict_types=1);

namespace Local\Token\Tests\Driver;

use Local\Token\TokenBuilderInterface;
use Local\Token\TokenParserInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group('local/token')]
final class SuccessfulParsingTest extends DriverTestCase
{
    #[DataProvider('driversDataProvider')]
    public function testDecodeAndEncode(TokenParserInterface $parser, TokenBuilderInterface $builder): void
    {
        $payload = \bin2hex(\random_bytes(42));

        $token = $builder->build([
            'test' => $payload,
        ]);

        self::assertNotSame('', $token);

        $data = $parser->parse($token);

        self::assertArrayHasKey('test', $data);
        self::assertSame($data['test'], $payload);
    }
}
