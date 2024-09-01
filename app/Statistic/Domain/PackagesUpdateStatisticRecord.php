<?php

declare(strict_types=1);

namespace App\Statistic\Domain;

use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
 *
 * @property-read int|null $id Annotation for PHP 8.4 autocompletion support
 * @property-read non-empty-string $ip Annotation for PHP 8.4 autocompletion support
 * @property-read non-empty-string|null $composerVersion Annotation for PHP 8.4 autocompletion support
 * @property-read non-empty-string|null $phpVersion Annotation for PHP 8.4 autocompletion support
 * @property-read non-empty-string|null $os Annotation for PHP 8.4 autocompletion support
 */
#[ORM\Entity]
#[ORM\Table(name: 'statistic_package_updates')]
class PackagesUpdateStatisticRecord implements CreatedDateProviderInterface
{
    use CreatedDateProvider;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public private(set) ?int $id = null;

    #[ORM\Column(name: 'ip', type: 'string', nullable: false)]
    public private(set) string $ip;

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
        $this->ip = $ip;
        $this->composerVersion = $composerVersion;
        $this->phpVersion = $phpVersion;
        $this->os = $os;
    }
}
