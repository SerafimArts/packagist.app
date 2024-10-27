<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Statistic;

use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
 */
#[ORM\Entity]
#[ORM\Table(name: 'statistic_downloads')]
class DownloadsStatisticRecord extends StatisticRecord
{
    #[ORM\Column(name: 'composer_version', type: 'string', nullable: true)]
    public private(set) ?string $composerVersion = null;

    #[ORM\Column(name: 'php_version', type: 'string', nullable: true)]
    public private(set) ?string $phpVersion = null;

    #[ORM\Column(name: 'os', type: 'string', nullable: true)]
    public private(set) ?string $os = null;

    /**
     * @param non-empty-string $ip
     * @param non-empty-string|null $composerVersion
     * @param non-empty-string|null $phpVersion
     * @param non-empty-string|null $os
     */
    public function __construct(
        string $ip,
        ?string $composerVersion = null,
        ?string $phpVersion = null,
        ?string $os = null,
    ) {
        parent::__construct($ip);

        $this->composerVersion = $composerVersion;
        $this->phpVersion = $phpVersion;
        $this->os = $os;
    }
}
