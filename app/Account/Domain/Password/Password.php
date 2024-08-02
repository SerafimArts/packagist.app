<?php

declare(strict_types=1);

namespace App\Account\Domain\Password;

use App\Account\Domain\Account;
use App\Account\Domain\Authentication;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Embeddable]
final class Password extends EncryptedPassword
{
    /**
     * @param non-empty-string $raw
     * @param non-empty-string $hash
     */
    public function __construct(
        #[\SensitiveParameter]
        public readonly string $raw,
        string $hash,
    ) {
        parent::__construct($hash);
    }

    /**
     * @api
     *
     * @param non-empty-string $value
     */
    public static function createForUser(
        UserPasswordHasherInterface $hasher,
        #[\SensitiveParameter]
        string $value,
        PasswordAuthenticatedUserInterface $user,
    ): self {
        /** @var non-empty-string $hash */
        $hash = $hasher->hashPassword($user, $value);

        return new self($value, $hash);
    }

    /**
     * @api
     *
     * @param non-empty-string $value
     */
    public static function createForAccount(
        UserPasswordHasherInterface $hasher,
        #[\SensitiveParameter]
        string $value,
        Account $account,
    ): self {
        return self::createForUser($hasher, $value, new Authentication(
            account: $account,
        ));
    }
}
