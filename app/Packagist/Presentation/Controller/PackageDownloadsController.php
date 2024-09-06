<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller;

use App\Packagist\Application\CollectStatistic\AddDownloadingRecordCommand;
use App\Packagist\Application\CollectStatistic\AddPackageDownloadingCommand;
use App\Packagist\Application\CollectStatistic\AddReleaseDownloadingCommand;
use App\Packagist\Presentation\Controller\PackageDownloadsController\PackageDownloadsRequestDTO;
use App\Packagist\Presentation\Controller\PackageDownloadsController\PackageDownloadsResponseDTO;
use App\Packagist\Presentation\Request\DTO\ClientInfoRequestDTO;
use App\Shared\Domain\Bus\CommandBusInterface;
use Local\HttpData\Attribute\MapBody;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This controller will be called every time a user installs a package.
 *
 * For "`example.org/packages.json`" containing a "`monolog/monolog`" package,
 * this would send a `POST` request to "`example.org/downloads/`" with following
 * JSON request body:
 *
 * ```
 * {
 *     "downloads": [
 *         {"name": "monolog/monolog", "version": "1.2.1.0"}
 *     ]
 * }
 * ```
 */
#[AsController]
#[Route('/downloads', name: 'package.downloads', methods: Request::METHOD_POST, stateless: true)]
final readonly class PackageDownloadsController
{
    public function __construct(
        private CommandBusInterface $commands,
    ) {}

    /**
     * @param PackageDownloadsRequestDTO $data Provides statistics data
     * @param ClientInfoRequestDTO $info Provides information about request client
     */
    public function __invoke(
        Request $request,
        #[MapBody]
        PackageDownloadsRequestDTO $data,
        ClientInfoRequestDTO $info,
    ): PackageDownloadsResponseDTO {
        $this->commands->send($downloading = new AddDownloadingRecordCommand(
            ip: $request->getClientIp() ?? '127.0.0.1',
            composerVersion: $info->composerVersion,
            phpVersion: $info->phpVersion,
            operatingSystem: $info->operatingSystem,
            ci: $info->ci,
        ));

        foreach ($data->downloads as $package) {
            $this->commands->send($packageDownloading = new AddPackageDownloadingCommand(
                name: $package->name,
                info: $downloading,
            ));

            if ($package->version !== null) {
                $this->commands->send(new AddReleaseDownloadingCommand(
                    version: $package->version,
                    package: $packageDownloading,
                ));
            }
        }

        return new PackageDownloadsResponseDTO();
    }
}
