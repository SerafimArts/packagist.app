<?php

declare(strict_types=1);

namespace App\Web\Presentation\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
#[Route(path: '/')]
final readonly class HomeController
{
    public function __construct(
        private Environment $twig,
    ) {}

    public function __invoke(): Response
    {
        $view = $this->twig->render('master.html.twig');

        return new Response($view);
    }
}
