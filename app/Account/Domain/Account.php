<?php

declare(strict_types=1);

namespace App\Account\Domain;

use App\Account\Domain\Password\EncryptedPassword;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProviderInterface;
use App\Shared\Domain\Id\AccountId;
use App\Shared\Domain\Id\IdentifiableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
 */
#[ORM\Entity]
#[ORM\Table(name: 'accounts')]
#[ORM\UniqueConstraint(name: 'login_unique', columns: ['login'])]
class Account implements
    IdentifiableInterface,
    CreatedDateProviderInterface,
    UpdatedDateProviderInterface
{
    use CreatedDateProvider;
    use UpdatedDateProvider;

    #[ORM\Id]
    #[ORM\Column(type: AccountId::class)]
    public AccountId $id { get => $this->id; }

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string', unique: true)]
    public string $login;

    #[ORM\Embedded(class: EncryptedPassword::class, columnPrefix: false)]
    public EncryptedPassword $password;

    /**
     * @var list<Role>
     */
    #[ORM\Column(type: Role::class . '[]', options: ['default' => '{}'])]
    public array $roles = [];

    /**
     * @param non-empty-string $login
     * @param iterable<array-key, Role>|Role $roles
     */
    public function __construct(
        string $login,
        EncryptedPassword $password = new EncryptedPassword(),
        iterable|Role $roles = [],
        ?AccountId $id = null,
    ) {
        $this->login = $login;
        $this->password = $password;
        $this->id = $id ?? AccountId::new();
        $this->roles = match (true) {
            $roles instanceof Role => [$roles],
            default => \array_values([...$roles]),
        };
        $this->createdAt = new \DateTimeImmutable();
    }
}
