<?php

declare(strict_types=1);

namespace App\Package\Domain;

use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProviderInterface;
use App\Shared\Domain\Id\IdentifiableInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
 *
 * @uses Collection (phpstorm reference bug)
 *
 * @property Credentials $credentials Annotation for PHP 8.4 autocompletion support
 * @property PackageVersionsSet $versions Annotation for PHP 8.4 autocompletion support
 */
#[ORM\Entity]
#[ORM\Table(name: 'packages')]
#[ORM\UniqueConstraint(name: 'package_name_idx', columns: ['name'])]
class Package implements
    IdentifiableInterface,
    CreatedDateProviderInterface,
    UpdatedDateProviderInterface
{
    use CreatedDateProvider;
    use UpdatedDateProvider;

    public function __construct(
        Credentials $credentials,
        ?PackageId $id = null,
    ) {
        $this->credentials = $credentials;
        $this->versions = new PackageVersionsSet();
        $this->id = $id ?? PackageId::new();
    }

    /**
     * @param non-empty-string $vendor
     * @param non-empty-string $name
     */
    public static function create(
        string $vendor,
        string $name,
        ?PackageId $id = null,
    ): self {
        return new self(
            credentials: new Credentials(
                vendor: $vendor,
                name: $name,
            ),
            id: $id,
        );
    }

    // -------------------------------------------------------------------------
    //  All properties are located AFTER the methods, because at the moment
    //  IDE does not support PHP 8.4
    // -------------------------------------------------------------------------

    #[ORM\Id]
    #[ORM\Column(type: PackageId::class)]
    public PackageId $id { get => $this->id; }

    #[ORM\Embedded(class: Credentials::class, columnPrefix: false)]
    public Credentials $credentials;

    /**
     * @var PackageVersionsSet<PackageVersion>
     * @readonly
     */
    #[ORM\OneToMany(targetEntity: PackageVersion::class, mappedBy: 'package', cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\OrderBy(['version' => 'DESC', 'createdAt' => 'ASC'])]
    public Collection $versions {
        get => PackageVersionsSet::for($this->versions);
    }
}
