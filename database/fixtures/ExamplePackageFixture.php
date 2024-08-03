<?php

declare(strict_types=1);

namespace App\Database\DataFixtures;

use App\Package\Domain\Credentials;
use App\Package\Domain\Package;
use Doctrine\Persistence\ObjectManager;

/**
 * @api
 */
final class ExamplePackageFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $package = new Package(new Credentials(
            name: 'example',
            vendor: 'example',
        ));

        $manager->persist($package);
        $manager->flush();
    }
}
