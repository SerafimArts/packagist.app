<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http;

use PHPUnit\Framework\Attributes\TestDox;

#[TestDox('GET /packages.json')]
final class RepositoryTest extends HttpTestCase
{
    public function testSuccessful(): void
    {
        $this->json('GET', '/packages.json')
            ->assertStatusOk()
            ->assertJsonSchemaFileMatches(__DIR__ . '/packages.json')
            ->assertJsonPathSame('$.packages', [])
            ->assertJsonPathStringContains('$.warning', 'Support for Composer 1 is deprecated')
            ->assertJsonPathSame('$.warning-versions', '<1.99')
            ->assertJsonPathSame('$.metadata-url', '/package/%package%.json')
        ;
    }
}
