<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Package;

use App\Packagist\Domain\Release;
use App\Packagist\Domain\Release\Reference\DistReference;
use App\Packagist\Domain\Release\Version;
use App\Tests\Concerns\InteractWithDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

#[TestDox('GET /package/meta/<vendor>/<name>.json')]
final class PackageMetaTest extends PackageVersionsTestCase
{
    use InteractWithDatabase;

    public function testInvalidPackage(): void
    {
        $this->json('GET', '/package/meta/unknown/pattern.json')
            ->assertStatus(404)
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.packagist.json')
            ->assertJsonPathSame('$.message', '404 not found, no packages here');
    }

    #[TestDox('Stable and dev packages in separate lists')]
    #[DataProvider('stabilityDataProvider')]
    public function testPackageSeparateLists(bool $stable, string $suffix): void
    {
        $name = $this->getPackageName($stable);

        $package = $this->givenPackage($name);

        $this->given(new Release(
            package: $package,
            version: new Version(
                value: '1.0',
                normalized: '1.0.0.0'
            ),
            isRelease: true,
            dist: new DistReference(
                type: 'git',
                url: 'http://localhost',
            ),
        ));

        $this->given(new Release(
            package: $package,
            version: new Version(
                value: '1.x-dev',
                normalized: '1.9999.9999.9999-dev',
            ),
            isRelease: false,
            dist: new DistReference(
                type: 'git',
                url: 'http://localhost',
            ),
        ));

        $this->json('GET', '/package/meta/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package-meta.json')
            ->assertJsonPathIsArrayOfSize('$.packages["test/' . $name . '"]', 1)
        ;
    }

    #[TestDox('Package versions with dist url')]
    #[DataProvider('stabilityDataProvider')]
    public function testVersionsWithDist(bool $stable, string $suffix): void
    {
        $name = $this->getPackageName($stable);

        $this->givenPackageVersionWithDist($name, $stable);

        $this->json('GET', '/package/meta/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package-meta.json')
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

        $this->json('GET', '/package/meta/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package-meta.json')
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

        $this->json('GET', '/package/meta/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package-meta.json')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].name', 'test/' . $name)
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version', '2.0')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version_normalized', '2.0.0.0')
            ->assertJsonPathIsObject('$.packages["test/' . $name . '"][0].dist')
            ->assertJsonPathIsObject('$.packages["test/' . $name . '"][0].source')
        ;
    }
}
