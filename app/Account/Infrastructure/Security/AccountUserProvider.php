<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Security;

use App\Account\Domain\AccountId;
use App\Account\Domain\AccountRepositoryInterface;
use App\Account\Domain\Authentication;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Account\Infrastructure\Security
 *
 * @template-implements UserProviderInterface<Authentication>
 */
final readonly class AccountUserProvider implements UserProviderInterface
{
    public function __construct(
        private AccountRepositoryInterface $accounts,
    ) {}

    public function refreshUser(UserInterface $user): Authentication
    {
        if (!$user instanceof Authentication) {
            throw new UnsupportedUserException();
        }

        $account = $user->getAccount();

        return $this->findAccountAuthentication($account->id);
    }

    public function supportsClass(string $class): bool
    {
        return $class === Authentication::class
            || \is_a($class, Authentication::class, true);
    }

    public function loadUserByIdentifier(string $identifier): Authentication
    {
        if ($identifier === '') {
            throw new UserNotFoundException('Invalid user identifier');
        }

        return $this->findAccountAuthentication(new AccountId($identifier));
    }

    /**
     * @throws UserNotFoundException
     */
    private function findAccountAuthentication(AccountId $id): Authentication
    {
        $account = $this->accounts->findById($id);

        if ($account === null) {
            throw new UserNotFoundException(
                message: \sprintf('User "%s" not found', $id->toString()),
            );
        }

        return new Authentication($account);
    }
}
