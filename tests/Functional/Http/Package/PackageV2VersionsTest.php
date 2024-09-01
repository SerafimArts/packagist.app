<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Package;

use App\Tests\Concerns\InteractWithDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

#[TestDox('GET /package/v2/<vendor>/<name>.json')]
final class PackageV2VersionsTest extends PackageVersionsTestCase
{
    use InteractWithDatabase;

    public function testInvalidPackage(): void
    {
        $this->json('GET', '/package/v2/unknown/pattern.json')
            ->assertStatus(404)
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.packagist.json')
            ->assertJsonPathSame('$.message', '404 not found, no packages here');
    }

    #[TestDox('Package versions with dist url')]
    #[DataProvider('stabilityDataProvider')]
    public function testVersionsWithDist(bool $stable, string $suffix): void
    {
        $name = $this->getPackageName($stable);

        $this->givenPackageVersionWithDist($name, $stable);

        $this->json('GET', '/package/v2/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package-v2.json')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].name', 'test/' . $name)
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version', '2.0')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version_normalized', '2.0.0.0')
            ->assertJsonPathIsObject('$.packages["test/' . $name . '"][0].dist')
        ;
    }

    #[TestDox('Package versions with source url')]
    #[DataProvider('stabilityDataProvider')]
    public function testVersionsWithSource(bool $stable, string $suffix): void
    {
        $name = $this->getPackageName($stable);

        $this->givenPackageVersionWithSource($name, $stable);

        $this->json('GET', '/package/v2/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package-v2.json')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].name', 'test/' . $name)
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version', '2.0')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version_normalized', '2.0.0.0')
            ->assertJsonPathIsObject('$.packages["test/' . $name . '"][0].source')
        ;
    }

    #[TestDox('Package versions with source url')]
    #[DataProvider('stabilityDataProvider')]
    public function testVersionsWithSourceAndDist(bool $stable, string $suffix): void
    {
        $name = $this->getPackageName($stable);

        $this->givenPackageVersionWithSourceAndDist($name, $stable);

        $this->json('GET', '/package/v2/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package-v2.json')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].name', 'test/' . $name)
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version', '2.0')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version_normalized', '2.0.0.0')
            ->assertJsonPathIsObject('$.packages["test/' . $name . '"][0].dist')
            ->assertJsonPathIsObject('$.packages["test/' . $name . '"][0].source')
        ;
    }
}
