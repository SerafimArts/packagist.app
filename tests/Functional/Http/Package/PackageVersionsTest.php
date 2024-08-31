<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Package;

use App\Package\Domain\Package;
use App\Package\Domain\Version\PackageVersion;
use App\Tests\Concerns\InteractWithDatabase;
use App\Tests\Functional\Http\HttpTestCase;
use Local\Testing\Http\TestingResponse;
use PHPUnit\Framework\Attributes\TestDox;

#[TestDox('GET /package/<vendor>/<name>.json')]
final class PackageVersionsTest extends HttpTestCase
{
    use InteractWithDatabase;

    public function testInvalidPackage(): void
    {
        $this->json('GET', '/package/unknown/pattern.json')
            ->assertStatus(404)
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.packagist.json')
            ->assertJsonPathSame('$.message', '404 not found, no packages here');
    }

    private function givenPackageVersion(string $name, bool $stable): void
    {
        $this->given(new PackageVersion(
            package: Package::create(
                vendor: 'test',
                name: $name,
            ),
            version: '2.0',
            isRelease: $stable,
        ));
    }

    private function assertValidPackageVersion(string $name, TestingResponse $response): void
    {
        $response
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].name', 'test/' . $name)
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version', '2.0')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version_normalized', '2.0.0.0')
        ;
    }

    public function testOnlyDevVersions(): void
    {
        $this->givenPackageVersion('test-dev', false);

        $response = $this->json('GET', '/package/test/test-dev~dev.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package.json')
        ;

        $this->assertValidPackageVersion('test-dev', $response);

        $this->json('GET', '/package/test/test-dev.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package.json')
            ->assertJsonPathSame('$.packages["test/test-dev"]', [])
        ;
    }

    public function testOnlyStableVersions(): void
    {
        $this->givenPackageVersion('test-stable', true);

        $response = $this->json('GET', '/package/test/test-stable.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package.json')
        ;

        $this->assertValidPackageVersion('test-stable', $response);

        $this->json('GET', '/package/test/test-stable~dev.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package.json')
            ->assertJsonPathSame('$.packages["test/test-stable"]', [])
        ;
    }
}
