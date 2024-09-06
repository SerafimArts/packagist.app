<?php

declare(strict_types=1);

namespace App\Packagist\Domain;

use App\Packagist\Domain\Release\LicensesSet;
use App\Packagist\Domain\Release\Reference\DistReference;
use App\Packagist\Domain\Release\Reference\SourceReference;
use App\Packagist\Domain\Release\Version;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProviderInterface;
use App\Shared\Domain\Id\IdentifiableInterface;
use App\Shared\Domain\Id\PackageId;
use App\Shared\Domain\Id\PackageReleaseId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
 *
 * @property SourceReference|null $source Annotation for PHP 8.4 autocompletion support
 * @property DistReference|null $dist Annotation for PHP 8.4 autocompletion support
 * @property-read LicensesSet $license Annotation for PHP 8.4 autocompletion support
 */
#[ORM\Entity]
#[ORM\Table(name: 'package_releases')]
class Release implements
    IdentifiableInterface,
    CreatedDateProviderInterface,
    UpdatedDateProviderInterface
{
    use CreatedDateProvider;
    use UpdatedDateProvider;

    #[ORM\ManyToOne(targetEntity: Package::class, cascade: ['ALL'], inversedBy: 'releases')]
    #[ORM\JoinColumn(name: 'package_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public readonly Package $package;

    #[ORM\Embedded(class: Version::class, columnPrefix: false)]
    public Version $version;

    #[ORM\Embedded(class: Version::class, columnPrefix: 'normalized_')]
    public Version $normalized;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    /**
     * @var list<non-empty-string>
     */
    #[ORM\Column(name: 'license', type: 'string[]', options: ['default' => '{}'])]
    private array $licenseValues = [];

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    public bool $isRelease;

    /**
     * @param non-empty-string|\Stringable $version
     * @param non-empty-string|\Stringable $normalized
     */
    public function __construct(
        Package $package,
        string|\Stringable $version,
        string|\Stringable $normalized,
        bool $isRelease = false,
        ?SourceReference $source = null,
        ?DistReference $dist = null,
        ?PackageId $id = null,
    ) {
        $this->package = $package;
        $this->version = new Version((string) $version);
        $this->normalized = new Version((string) $normalized);
        $this->isRelease = $isRelease;
        $this->source = $source ?? SourceReference::createEmpty();
        $this->dist = $dist ?? DistReference::createEmpty();
        $this->id = $id ?? PackageReleaseId::new();

        $package->releases->add($this);
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
    #[ORM\Column(type: PackageReleaseId::class)]
    public private(set) PackageReleaseId $id;

    /**
     * @readonly impossible to specify "readonly" attribute natively due
     *           to a Doctrine feature/bug https://github.com/doctrine/orm/issues/9863
     */
    public private(set) LicenseSet $license {
        get => LicenseSet::for($this->licenseValues);
    }

    #[ORM\Embedded(class: SourceReference::class, columnPrefix: 'source_')]
    public ?SourceReference $source {
        get => $this->source?->isValid() ? $this->source : null;
        set (?SourceReference $ref) => $ref ?? SourceReference::createEmpty();
    }

    #[ORM\Embedded(class: DistReference::class, columnPrefix: 'dist_')]
    public ?DistReference $dist {
        get => $this->dist?->isValid() ? $this->dist : null;
        set (?DistReference $ref) => $ref ?? DistReference::createEmpty();
    }
}
