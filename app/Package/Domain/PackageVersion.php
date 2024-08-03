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

    #[ORM\Id]
    #[ORM\Column(type: PackageVersionId::class)]
    public PackageVersionId $id { get => $this->id; }

    #[ORM\Column(type: 'string', options: ['default' => '0.0.1'])]
    public string $version;

    #[ORM\ManyToOne(targetEntity: Package::class, cascade: ['ALL'], inversedBy: 'versions')]
    #[ORM\JoinColumn(name: 'package_id', referencedColumnName: 'id')]
    public Package $package;

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
        $this->id = $id ?? PackageVersionId::new();

        $package->versions->add($this);
    }
}
