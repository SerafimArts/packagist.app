<?php

declare(strict_types=1);

namespace App\Database\DataFixtures;

use App\Package\Domain\Credentials;
use App\Package\Domain\Package;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @api
 */
final class PackagesFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 100; ++$i) {
            echo $this->progressNext('Generating accounts...');

            $package = new Package(new Credentials(
                vendor: \strtolower($faker->userName()),
                name: \strtolower($faker->userName()),
            ));

            $manager->persist($package);
        }

        echo "\r";

        $manager->flush();
    }
}
