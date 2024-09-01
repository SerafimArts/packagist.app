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

    protected function givenPackageVersion(string $name, bool $stable = true): PackageVersion
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

    protected function givenPackageVersionWithDist(string $name, bool $stable = true): PackageVersion
    {
        $version = $this->givenPackageVersion($name, $stable);
        $version->dist = new DistReference('zip', 'http://localhost/example.zip');

        return $this->given($version);
    }

    protected function givenPackageVersionWithSource(string $name, bool $stable = true): PackageVersion
    {
        $version = $this->givenPackageVersion($name, $stable);
        $version->source = new SourceReference('zip', 'http://localhost/example.zip', 'deadbeef');

        return $this->given($version);
    }

    protected function givenPackageVersionWithSourceAndDist(string $name, bool $stable = true): PackageVersion
    {
        $version = $this->givenPackageVersion($name, $stable);
        $version->dist = new DistReference('zip', 'http://localhost/example.zip');
        $version->source = new SourceReference('zip', 'http://localhost/example.zip', 'deadbeef');

        return $this->given($version);
    }
}
