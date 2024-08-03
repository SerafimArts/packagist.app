<?php

declare(strict_types=1);

namespace App\Package\Domain;

use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProviderInterface;
use App\Shared\Domain\Id\IdentifiableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
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
     *           to a Doctrine bug https://github.com/doctrine/orm/issues/9863
     */
    #[ORM\Id]
    #[ORM\Column(type: PackageVersionId::class)]
    public PackageVersionId $id;

    #[ORM\Embedded(class: Version::class, columnPrefix: 'version_')]
    public Version $version;

    #[ORM\ManyToOne(targetEntity: Package::class, cascade: ['ALL'], inversedBy: 'versions')]
    #[ORM\JoinColumn(name: 'package_id', referencedColumnName: 'id')]
    public Package $package;

    /**
     * @param non-empty-string|\Stringable $version
     */
    public function __construct(
        Package $package,
        string|\Stringable $version,
        ?PackageId $id = null,
    ) {
        $this->package = $package;
        $this->version = match (true) {
            $version instanceof Version => $version,
            default => new Version((string) $version),
        };
        $this->id = $id ?? PackageVersionId::new();

        $package->versions->add($this);
    }
}
