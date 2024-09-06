<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Package;

use App\Packagist\Domain\Package;
use App\Packagist\Domain\Release;
use App\Packagist\Domain\Release\Reference\DistReference;
use App\Packagist\Domain\Release\Reference\SourceReference;
use App\Packagist\Domain\Release\Version;
use App\Tests\Concerns\InteractWithDatabase;
use App\Tests\Functional\Http\HttpTestCase;

abstract class PackageVersionsTestCase extends HttpTestCase
{
    use InteractWithDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return non-empty-string
     */
    protected function getPackageName(bool $stable): string
    {
        $function = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS)[1]['function'];

        return \hash('xxh3', static::class . '@' . $function)
             . ($stable ? '-stable' : '-dev');
    }

    public static function stabilityDataProvider(): iterable
    {
        yield 'stable' => [true, ''];
        yield 'dev' => [false, '~dev'];
    }

    protected function givenPackage(string $name): Package
    {
        return $this->given(Package::create(
            name: $name,
            vendor: 'test',
        ));
    }

    private function createPackageVersion(string $name, bool $stable = true): Release
    {
        return new Release(
            package: $this->givenPackage($name),
            version: new Version(
                value: '2.0',
                normalized: '2.0.0.0',
            ),
            isRelease: $stable,
        );
    }

    protected function givenPackageVersion(string $name, bool $stable = true): Release
    {
        return $this->given($this->createPackageVersion($name, $stable));
    }

    protected function givenPackageVersionWithDist(string $name, bool $stable = true): Release
    {
        $version = $this->createPackageVersion($name, $stable);
        $version->dist = new DistReference('zip', 'http://localhost/example.zip');

        return $this->given($version);
    }

    protected function givenPackageVersionWithSource(string $name, bool $stable = true): Release
    {
        $version = $this->createPackageVersion($name, $stable);
        $version->source = new SourceReference('zip', 'http://localhost/example.zip', 'deadbeef');

        return $this->given($version);
    }

    protected function givenPackageVersionWithSourceAndDist(string $name, bool $stable = true): Release
    {
        $version = $this->createPackageVersion($name, $stable);
        $version->dist = new DistReference('zip', 'http://localhost/example.zip');
        $version->source = new SourceReference('zip', 'http://localhost/example.zip', 'deadbeef');

        return $this->given($version);
    }
}
