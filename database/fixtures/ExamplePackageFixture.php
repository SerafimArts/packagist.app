<?php

declare(strict_types=1);

namespace App\Database\DataFixtures;

use App\Package\Domain\Credentials;
use App\Package\Domain\Package;
use App\Package\Domain\PackageVersion;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @api
 */
final class ExamplePackageFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $package = new Package(new Credentials(
            vendor: 'example',
            name: 'example',
        ));

        for ($i = 0; $i < 100; ++$i) {
            echo $this->progressNext('Generating example/example package versions...');

            new PackageVersion(
                package: $package,
                version: \vsprintf('%d.%d.%d', [
                    \random_int(0, 3),
                    \random_int(0, 8),
                    \random_int(0, 32),
                ]),
                isRelease: true,
            );
        }

        for ($i = 0; $i < 30; ++$i) {
            echo $this->progressNext('Generating example/example dev package versions...');

            new PackageVersion(
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
        }

        echo "\r";

        $manager->persist($package);
        $manager->flush();
    }
}
