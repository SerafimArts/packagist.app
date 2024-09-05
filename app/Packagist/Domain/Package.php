<?php

declare(strict_types=1);

namespace App\Packagist\Domain;

use App\Packagist\Domain\Release\PackageRelease;
use App\Packagist\Domain\Release\PackageReleasesSet;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProviderInterface;
use App\Shared\Domain\Id\IdentifiableInterface;
use App\Shared\Domain\Id\PackageId;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
 *
 * @uses Collection (phpstorm reference bug)
 *
 * @property Name $credentials Annotation for PHP 8.4 autocompletion support
 * @property PackageReleasesSet $versions Annotation for PHP 8.4 autocompletion support
 */
#[ORM\Entity]
#[ORM\Table(name: 'packages')]
#[ORM\UniqueConstraint(name: 'package_name_idx', columns: ['name', 'vendor'])]
class Package implements
    IdentifiableInterface,
    CreatedDateProviderInterface,
    UpdatedDateProviderInterface
{
    use CreatedDateProvider;
    use UpdatedDateProvider;

    #[ORM\Embedded(class: Name::class, columnPrefix: false)]
    public Name $name;

    /**
     * @param Name|non-empty-string $name
     */
    public function __construct(
        Name|string $name,
        ?PackageId $id = null,
    ) {
        $this->name = Name::create($name);
        $this->versions = new PackageReleasesSet();
        $this->id = $id ?? PackageId::new();
    }

    /**
     * @param non-empty-string $name
     * @param non-empty-string|null $vendor
     */
    public static function create(
        string $name,
        ?string $vendor = null,
        ?PackageId $id = null,
    ): self {
        return new self(
            name: new Name(
                name: $name,
                vendor: $vendor,
            ),
            id: $id,
        );
    }

    // -------------------------------------------------------------------------
    //  All properties are located AFTER the methods, because at the moment
    //  IDE does not support PHP 8.4
    // -------------------------------------------------------------------------

    /**
     * @readonly impossible to specify "readonly" attribute natively due
     *           to a Doctrine feature/bug https://github.com/doctrine/orm/issues/9863
     */
    #[ORM\Id]
    #[ORM\Column(type: PackageId::class)]
    public private(set) PackageId $id;

    /**
     * @readonly
     */
    #[ORM\OneToMany(targetEntity: PackageRelease::class, mappedBy: 'package', cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\OrderBy(['version' => 'DESC', 'createdAt' => 'ASC'])]
    public private(set) Collection $versions {
        get => PackageReleasesSet::for($this->versions);
    }
}
