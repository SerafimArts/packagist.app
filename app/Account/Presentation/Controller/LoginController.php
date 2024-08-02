<?php

declare(strict_types=1);

namespace App\Account\Presentation\Controller;

use App\Account\Application\Auth\AuthenticationProcess;
use App\Account\Application\Auth\Exception\AuthenticationFailedException;
use App\Account\Presentation\Controller\LoginController\LoginRequestDTO;
use App\Account\Presentation\Controller\LoginController\LoginResponseDTO;
use App\Account\Presentation\Controller\LoginController\LoginResponseTransformer;
use App\Shared\Presentation\Exception\PresentationException;
use Local\HttpData\Attribute\MapBody;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/auth/login', methods: Request::METHOD_POST, stateless: true)]
final readonly class LoginController
{
    public function __construct(
        private AuthenticationProcess $auth,
        private LoginResponseTransformer $response,
    ) {}

    public function __invoke(#[MapBody] LoginRequestDTO $request): LoginResponseDTO
    {
        try {
            return $this->response->transform($this->auth->login(
                login: $request->login,
                password: $request->password,
            ));
        } catch (AuthenticationFailedException $e) {
            throw PresentationException::fromApplicationException($e);
        }
    }
}
