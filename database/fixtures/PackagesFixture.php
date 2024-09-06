<?php

declare(strict_types=1);

namespace App\Database\DataFixtures;

use App\Packagist\Domain\Name;
use App\Packagist\Domain\Package;
use App\Packagist\Domain\Release;
use App\Packagist\Domain\Release\Reference\DistReference;
use App\Packagist\Domain\Release\Reference\SourceReference;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Packagist\Domain\Release\Version\VersionParser;
use Faker\Generator;

/**
 * @api
 */
final class PackagesFixture extends Fixture
{
    public function __construct(
        private readonly VersionParser $parser = new VersionParser(),
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (['example', 'example-1', 'test'] as $name) {
            $package = $this->createPackage($faker, new Name(
                name: $name,
                vendor: 'example',
            ));

            $manager->persist($package);
            $manager->flush();
        }

        $vendor = \strtolower($faker->firstName());
        for ($i = 0; $i < 100; ++$i) {
            // 50% to generate new vendor
            if (\random_int(0, 1)) {
                $vendor = \strtolower($faker->firstName());
            }

            $name = \strtolower($faker->firstName());

            $package = $this->createPackage($faker, new Name(
                name: $name,
                vendor: $vendor,
            ));

            $manager->persist($package);
            $manager->flush();
        }
    }

    private function generateVersion(Generator $faker): string
    {
        $suffix = match (\random_int(0, 8)) {
            // 1 digit chance 1/9
            0 => (string)  $faker->randomNumber(1),
            // 2 digits chance 2/9
            1, 2 => \vsprintf('%d.%d', [
                 $faker->randomNumber(1),
                 $faker->randomNumber(2),
            ]),
            // 3 digits chance 4/9 (most popular)
            3, 4, 5, 6 => \vsprintf('%d.%d.%d', [
                 $faker->randomNumber(1),
                 $faker->randomNumber(2),
                 $faker->randomNumber(2),
            ]),
            // 4 digits chance 1/9
            7 => \vsprintf('%d.%d.%d.%d', [
                $faker->randomNumber(1),
                $faker->randomNumber(2),
                $faker->randomNumber(2),
                $faker->randomNumber(2),
            ]),
            // year-style chance 1/9
            default => \vsprintf('%d-%d-%d', [
                $faker->randomNumber(4,true),
                $faker->randomNumber(2,true),
                $faker->randomNumber(2,true),
            ]),
        };

        return match (\random_int(0, 2)) {
            0, 1 => $suffix,
            default => 'v' . $suffix,
        };
    }

    private function generateBranch(Generator $faker): string
    {
        $name = \strtolower($faker->userName());

        return match (\random_int(0, 7)) {
            // Just branch name chance 1/8
            0 => $name,
            // "feature/x", "release/x" or "hotfix/x" prefixed chance 2/8
            1, 2 => match (\random_int(0, 2)) {
                0 => "feature/$name",
                1 => "hotfix/$name",
                default => "release/$name",
            },
            // "dev-" prefixed chance 1/8
            3 => "dev-$name",
            // "1.x" chance 2/8
            4, 5 => $faker->randomNumber(2)
                . '.x',
            // "1.0.x" chance 2/8
            default => $faker->randomNumber(2) . '.'
                . $faker->randomNumber(2)
                . '.x',
        };
    }

    private function createPackage(Generator $faker, Name $name): Package
    {
        $package = new Package($name);

        for ($i = 0; $i < 100; ++$i) {
            echo $this->progressNext('Generating ' . $name . ' package versions...');

            $version = $this->parser->parse($this->generateVersion($faker), true);

            $release = new Release(
                package: $package,
                version: $version->version,
                normalized: $version->normalized,
                isRelease: true,
            );

            $this->addSourceOrDist($release);
        }

        for ($i = 0; $i < 30; ++$i) {
            echo $this->progressNext('Generating ' . $name . ' dev package versions...');

            $branch = $this->parser->parse($this->generateBranch($faker), false);

            $release = new Release(
                package: $package,
                version: $branch->version,
                normalized: $branch->normalized,
                isRelease: false,
            );

            $this->addSourceOrDist($release);
        }

        echo "\r";

        return $package;
    }

    private function addSourceOrDist(Release $version): void
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
