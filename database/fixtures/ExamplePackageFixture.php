<?php

declare(strict_types=1);

namespace App\Database\DataFixtures;

use App\Packagist\Domain\Name;
use App\Packagist\Domain\Package;
use App\Packagist\Domain\Version\PackageVersion;
use App\Packagist\Domain\Version\Reference\DistReference;
use App\Packagist\Domain\Version\Reference\SourceReference;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @api
 */
final class ExamplePackageFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (['example', 'example-1', 'test'] as $name) {
            $package = $this->createPackage($name);

            $manager->persist($package);
            $manager->flush();
        }
    }

    private function createPackage(string $name): Package
    {
        $faker = Factory::create();

        $package = new Package(new Name(
            name: $name,
            vendor: 'example',
        ));

        for ($i = 0; $i < 100; ++$i) {
            echo $this->progressNext('Generating example/example package versions...');

            $version = new PackageVersion(
                package: $package,
                version: \vsprintf('%d.%d.%d', [
                    \random_int(0, 3),
                    \random_int(0, 8),
                    \random_int(0, 32),
                ]),
                isRelease: true,
            );

            $this->addSourceOrDist($version);
        }

        for ($i = 0; $i < 30; ++$i) {
            echo $this->progressNext('Generating example/example dev package versions...');

            $version = new PackageVersion(
                package: $package,
                version: match (\random_int(0, 4)) {
                    0 => 'dev-' . \strtolower($faker->userName()),
                    1 => \random_int(0, 5) . '.x-dev',
                    2 => \strtolower($faker->userName()),
                    3 => match (\random_int(0, 1)) {
                        0 => 'feature/' . \strtolower($faker->userName()),
                        default => 'hotfix/' . \strtolower($faker->userName()),
                    },
                    default => \random_int(0, 5) . '.x',
                },
                isRelease: false,
            );

            $this->addSourceOrDist($version);
        }

        echo "\r";

        return $package;
    }

    private function addSourceOrDist(PackageVersion $version): void
    {
        if (\random_int(0, 2) === 0) {
            $version->dist = new DistReference(
                type: 'zip',
                url: 'https://api.github.com/repos/phplrt/phplrt/zipball/2d2745637cc9136189e5b6ef769657872919d32a',
                hash: '2d2745637cc9136189e5b6ef769657872919d32a',
            );

            if (\random_int(0, 2) === 0) {
                return;
            }
        }

        $version->source = new SourceReference(
            type: 'git',
            url: 'https://github.com/phplrt/phplrt.git',
            hash: '2d2745637cc9136189e5b6ef769657872919d32a',
        );
    }
}
