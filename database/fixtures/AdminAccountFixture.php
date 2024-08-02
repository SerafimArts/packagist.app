<?php

declare(strict_types=1);

namespace App\Database\DataFixtures;

use App\Account\Domain\Account;
use App\Account\Domain\Password\Password;
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
        $account = new Account('admin');
        $account->password = Password::createForAccount(
            hasher: $this->hasher,
            value: 'admin',
            account: $account,
        );

        $manager->persist($account);
        $manager->flush();
    }
}
