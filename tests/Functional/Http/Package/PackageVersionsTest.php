<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Package;

use App\Package\Domain\Package;
use App\Package\Domain\Version\PackageVersion;
use App\Package\Domain\Version\Reference\DistReference;
use App\Package\Domain\Version\Reference\SourceReference;
use App\Tests\Concerns\InteractWithDatabase;
use App\Tests\Functional\Http\HttpTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;

#[TestDox('GET /package/<vendor>/<name>.json')]
final class PackageVersionsTest extends HttpTestCase
{
    use InteractWithDatabase;

    public static function stabilityDataProvider(): iterable
    {
        yield 'stable' => [true, ''];
        yield 'dev' => [false, '~dev'];
    }

    public function testInvalidPackage(): void
    {
        $this->json('GET', '/package/unknown/pattern.json')
            ->assertStatus(404)
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.packagist.json')
            ->assertJsonPathSame('$.message', '404 not found, no packages here');
    }

    private function givenPackageVersion(string $name, bool $stable = true): PackageVersion
    {
        return $this->given(new PackageVersion(
            package: Package::create(
                vendor: 'test',
                name: $name,
            ),
            version: '2.0',
            isRelease: $stable,
        ));
    }

    private function givenPackageVersionWithDist(string $name, bool $stable = true): PackageVersion
    {
        $version = $this->givenPackageVersion($name, $stable);
        $version->dist = new DistReference('zip', 'http://localhost/example.zip');

        return $this->given($version);
    }

    private function givenPackageVersionWithSource(string $name, bool $stable = true): PackageVersion
    {
        $version = $this->givenPackageVersion($name, $stable);
        $version->source = new SourceReference('zip', 'http://localhost/example.zip', 'deadbeef');

        return $this->given($version);
    }

    private function givenPackageVersionWithSourceAndDist(string $name, bool $stable = true): PackageVersion
    {
        $version = $this->givenPackageVersion($name, $stable);
        $version->dist = new DistReference('zip', 'http://localhost/example.zip');
        $version->source = new SourceReference('zip', 'http://localhost/example.zip', 'deadbeef');

        return $this->given($version);
    }

    #[TestDox('Package versions without source or dist SHOULD not be returned')]
    #[DataProvider('stabilityDataProvider')]
    public function testSkipPackagesWithoutSourceOrDist(bool $stable, string $suffix): void
    {
        $name = \strtolower(__FUNCTION__) . ($stable ? '-stable' : '-dev');

        $this->givenPackageVersion($name, $stable);

        $this->json('GET', '/package/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package.json')
            ->assertJsonPathSame('$.packages["test/' . $name .'"]', [])
        ;
    }

    #[TestDox('Package versions with dist url')]
    #[DataProvider('stabilityDataProvider')]
    public function testVersionsWithDist(bool $stable, string $suffix): void
    {
        $name = \strtolower(__FUNCTION__) . ($stable ? '-stable' : '-dev');

        $this->givenPackageVersionWithDist($name, $stable);

        $this->json('GET', '/package/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package.json')
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
        $name = \strtolower(__FUNCTION__) . ($stable ? '-stable' : '-dev');

        $this->givenPackageVersionWithSource($name, $stable);

        $this->json('GET', '/package/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package.json')
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
        $name = \strtolower(__FUNCTION__) . ($stable ? '-stable' : '-dev');

        $this->givenPackageVersionWithSourceAndDist($name, $stable);

        $this->json('GET', '/package/test/' . $name . $suffix . '.json')
            ->assertSuccessful()
            ->assertJsonSchemaFileMatches(__DIR__ . '/package.json')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].name', 'test/' . $name)
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version', '2.0')
            ->assertJsonPathSame('$.packages["test/' . $name . '"][0].version_normalized', '2.0.0.0')
            ->assertJsonPathIsObject('$.packages["test/' . $name . '"][0].dist')
            ->assertJsonPathIsObject('$.packages["test/' . $name . '"][0].source')
        ;
    }
}
