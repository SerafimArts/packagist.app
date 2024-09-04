<?php

declare(strict_types=1);

namespace App\Statistic\Domain;

use App\Packagist\Domain\Name;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
 *
 * @property-read Name $name Annotation for PHP 8.4 autocompletion support
 * @property-read non-empty-string $version Annotation for PHP 8.4 autocompletion support
 */
#[ORM\Entity]
#[ORM\Table(name: 'statistic_package_downloads')]
class PackageDownloadsStatisticRecord extends StatisticRecord
{
    #[ORM\Embedded(class: Name::class, columnPrefix: false)]
    public private(set) Name $name;

    #[ORM\Column(name: 'version', type: 'string')]
    public private(set) string $version;

    /**
     * @param non-empty-string $ip
     * @param non-empty-string|Name $name
     * @param non-empty-string $version
     */
    public function __construct(
        string $ip,
        Name|string $name,
        string $version,
    ) {
        parent::__construct($ip);

        $this->name = Name::create($name);
        $this->version = $version;
    }
}
