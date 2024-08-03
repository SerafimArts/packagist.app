<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http;

final class PackagesTest extends HttpTestCase
{
    public function testSuccessful(): void
    {
        $this->json('GET', '/packages.json')
            ->assertStatusOk()
            ->assertJsonSchemaFileMatches(__DIR__ . '/packages.json');
    }
}