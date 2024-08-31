<?php

declare(strict_types=1);

namespace App\Database\DataFixtures;

use App\Account\Domain\Account;
use App\Account\Domain\Integration\Integration;
use App\Account\Domain\Password\Password;
use App\Account\Domain\Role;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @api
 */
final class AdminAccountFixture extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $account = new Account(
            login: 'admin',
            roles: [Role::SuperAdmin],
        );
        $account->password = Password::createForAccount(
            hasher: $this->hasher,
            value: 'admin',
            account: $account,
        );

        $account->integrations->add(new Integration(
            account: $account,
            dsn: 'github://default',
            externalId: '2461257',
            login: 'SerafimArts',
            email: 'nesk@xakep.ru',
            avatar: 'https://avatars.githubusercontent.com/u/2461257?v=4',
        ));

        $account->integrations->add(new Integration(
            account: $account,
            dsn: 'fake://localhost',
            externalId: '42'
        ));

        $account->integrations->add(new Integration(
            account: $account,
            dsn: 'gitlab://example.com',
            externalId: 'test',
            login: 'SerafimArts',
            email: 'sample@example.com',
        ));

        $manager->persist($account);
        $manager->flush();
    }
}
