<?php

declare(strict_types=1);

namespace App\Database\DataFixtures;

use App\Account\Domain\Account;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @api
 */
final class AccountsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 100; ++$i) {
            echo $this->progressNext('Generating accounts...');

            $manager->persist(new Account(
                // @phpstan-ignore-next-line
                login: $faker->unique()
                    ->userName(),
            ));
        }

        echo "\r";

        $manager->flush();
    }
}
