<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Package;

use App\Package\Domain\Package;
use App\Package\Domain\Version\PackageVersion;
use App\Package\Domain\Version\Reference\DistReference;
use App\Package\Domain\Version\Reference\SourceReference;
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

    private function createPackageVersion(string $name, bool $stable = true): PackageVersion
    {
        return new PackageVersion(
            package: $this->givenPackage($name),
            version: '2.0',
            isRelease: $stable,
        );
    }

    protected function givenPackageVersion(string $name, bool $stable = true): PackageVersion
    {
        return $this->given($this->createPackageVersion($name, $stable));
    }

    protected function givenPackageVersionWithDist(string $name, bool $stable = true): PackageVersion
    {
        $version = $this->createPackageVersion($name, $stable);
        $version->dist = new DistReference('zip', 'http://localhost/example.zip');

        return $this->given($version);
    }

    protected function givenPackageVersionWithSource(string $name, bool $stable = true): PackageVersion
    {
        $version = $this->createPackageVersion($name, $stable);
        $version->source = new SourceReference('zip', 'http://localhost/example.zip', 'deadbeef');

        return $this->given($version);
    }

    protected function givenPackageVersionWithSourceAndDist(string $name, bool $stable = true): PackageVersion
    {
        $version = $this->createPackageVersion($name, $stable);
        $version->dist = new DistReference('zip', 'http://localhost/example.zip');
        $version->source = new SourceReference('zip', 'http://localhost/example.zip', 'deadbeef');

        return $this->given($version);
    }
}
