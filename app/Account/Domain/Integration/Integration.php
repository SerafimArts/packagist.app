<?php

declare(strict_types=1);

namespace App\Account\Domain\Integration;

use App\Account\Domain\Account;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProviderInterface;
use App\Shared\Domain\Id\IntegrationId;
use App\Shared\Domain\Id\IdentifiableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
 */
#[ORM\Entity]
#[ORM\Table(name: 'account_integrations')]
class Integration implements
    IdentifiableInterface,
    CreatedDateProviderInterface,
    UpdatedDateProviderInterface
{
    use CreatedDateProvider;
    use UpdatedDateProvider;

    /**
     * @readonly impossible to specify "readonly" attribute natively due
     *           to a Doctrine feature/bug https://github.com/doctrine/orm/issues/9863
     */
    #[ORM\Id]
    #[ORM\Column(type: IntegrationId::class)]
    public IntegrationId $id;

    #[ORM\ManyToOne(targetEntity: Account::class, cascade: ['ALL'], inversedBy: 'integrations')]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id')]
    public readonly Account $account;

    #[ORM\Embedded(class: Dsn::class, columnPrefix: false)]
    public Dsn $dsn;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string')]
    public string $externalId;

    /**
     * @var non-empty-string|null
     */
    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $login = null;

    /**
     * @var non-empty-string|null
     */
    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $email = null;

    /**
     * @var non-empty-string|null
     */
    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $avatar = null;

    /**
     * @param non-empty-string|Dsn $dsn
     * @param non-empty-string $externalId
     * @param non-empty-string|null $login
     * @param non-empty-string|null $email
     * @param non-empty-string|null $avatar
     */
    public function __construct(
        Account $account,
        string|Dsn $dsn,
        string $externalId,
        ?string $login = null,
        ?string $email = null,
        ?string $avatar = null,
        ?IntegrationId $id = null,
    ) {
        $this->account = $account;
        $this->dsn = new Dsn((string) $dsn);
        $this->externalId = $externalId;
        $this->login = $login;
        $this->email = $email;
        $this->avatar = $avatar;
        $this->id = $id ?? IntegrationId::new();
        $this->createdAt = new \DateTimeImmutable();
    }
}
