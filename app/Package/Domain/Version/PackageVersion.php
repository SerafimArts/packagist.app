<?php

declare(strict_types=1);

namespace App\Package\Domain\Version;

use App\Package\Domain\Package;
use App\Package\Domain\Version\Reference\DistReference;
use App\Package\Domain\Version\Reference\SourceReference;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProviderInterface;
use App\Shared\Domain\Id\IdentifiableInterface;
use App\Shared\Domain\Id\PackageId;
use App\Shared\Domain\Id\PackageVersionId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
 *
 * @property SourceReference|null $source Annotation for PHP 8.4 autocompletion support
 * @property DistReference|null $dist Annotation for PHP 8.4 autocompletion support
 */
#[ORM\Entity]
#[ORM\Table(name: 'package_versions')]
class PackageVersion implements
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
    #[ORM\Column(type: PackageVersionId::class)]
    public PackageVersionId $id;

    #[ORM\ManyToOne(targetEntity: Package::class, cascade: ['ALL'], inversedBy: 'versions')]
    #[ORM\JoinColumn(name: 'package_id', referencedColumnName: 'id')]
    public Package $package;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string', options: ['default' => '0.0.1'])]
    public string $version;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    /**
     * @var list<non-empty-string>
     */
    #[ORM\Column(type: 'string[]', options: ['default' => '{}'])]
    public array $license = [];

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    public bool $isRelease;

    /**
     * @param non-empty-string $version
     */
    public function __construct(
        Package $package,
        string $version,
        bool $isRelease = false,
        ?PackageId $id = null,
    ) {
        $this->package = $package;
        $this->version = $version;
        $this->isRelease = $isRelease;
        $this->source = SourceReference::createEmpty();
        $this->dist = DistReference::createEmpty();
        $this->id = $id ?? PackageVersionId::new();

        $package->versions->add($this);
    }

    // -------------------------------------------------------------------------
    //  All properties are located AFTER the methods, because at the moment
    //  IDE does not support PHP 8.4
    // -------------------------------------------------------------------------

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
