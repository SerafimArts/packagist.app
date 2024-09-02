<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller;

use App\Package\Presentation\Controller\PackageDownloadsController\PackageDownloadsRequestDTO;
use App\Package\Presentation\Controller\PackageDownloadsController\PackageDownloadsResponseDTO;
use App\Shared\Domain\Bus\EventBusInterface;
use App\Statistic\Domain\Event\DownloadedEvent;
use App\Statistic\Domain\Event\PackageDownloadedEvent;
use Local\HttpData\Attribute\MapBody;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/downloads', name: 'package.downloads', methods: Request::METHOD_POST, stateless: true)]
final readonly class PackageDownloadsController
{
    public function __construct(
        private EventBusInterface $events,
    ) {}

    public function __invoke(
        Request $request,
        #[MapBody]
        PackageDownloadsRequestDTO $data,
    ): PackageDownloadsResponseDTO {
        $this->events->dispatch($event = new DownloadedEvent(
            ip: $request->getClientIp() ?? '127.0.0.1',
            userAgent: $request->headers->get('user-agent'),
        ));

        foreach ($data->downloads as $package) {
            $this->events->dispatch(new PackageDownloadedEvent(
                parent: $event,
                name: $package->name,
                version: $package->version,
            ));
        }

        return new PackageDownloadsResponseDTO();
    }
}
