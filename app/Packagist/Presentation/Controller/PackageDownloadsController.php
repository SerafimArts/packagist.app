<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller;

use App\Packagist\Application\CollectStatistic\AddDownloadingRecordCommand;
use App\Packagist\Application\CollectStatistic\AddReleaseDownloadingCommand;
use App\Packagist\Presentation\Controller\PackageDownloadsController\PackageDownloadsRequestDTO;
use App\Packagist\Presentation\Controller\PackageDownloadsController\PackageDownloadsResponseDTO;
use App\Packagist\Presentation\Request\DTO\ClientInfoRequestDTO;
use App\Shared\Domain\Bus\CommandBusInterface;
use Local\HttpData\Attribute\MapBody;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/downloads', name: 'package.downloads', methods: Request::METHOD_POST, stateless: true)]
final readonly class PackageDownloadsController
{
    public function __construct(
        private CommandBusInterface $commands,
    ) {}

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
            $this->commands->send(new AddReleaseDownloadingCommand(
                name: $package->name,
                version: $package->version,
                info: $downloading,
            ));
        }

        return new PackageDownloadsResponseDTO();
    }
}
