<?php

declare(strict_types=1);

namespace App\Statistic\Infrastructure\EventListener;

use App\Shared\Domain\Bus\EventBusInterface;
use App\Statistic\Domain\Event\PackagesUpdateRequested;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @api
 */
#[AsEventListener(event: 'kernel.request')]
final readonly class UpdatePackagesRequestEventListener
{
    /**
     * @var non-empty-string
     */
    private string $expectedRoute;

    public function __construct(
        private EventBusInterface $events,
        private UrlGeneratorInterface $generator,
    ) {
        $this->expectedRoute = '/' . $this->generator->generate(
            name: 'repository',
            referenceType: UrlGeneratorInterface::RELATIVE_PATH,
        );
    }

    private function isExpectedRequest(Request $request): bool
    {
        return $request->getPathInfo() === $this->expectedRoute;
    }

    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->isExpectedRequest($request)) {
            return;
        }

        $this->events->dispatch(new PackagesUpdateRequested(
            ip: $request->getClientIp() ?? '127.0.0.1',
            userAgent: $request->headers->get('user-agent'),
        ));
    }
}
