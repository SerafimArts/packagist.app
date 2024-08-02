<?php

declare(strict_types=1);

namespace App\Account\Presentation\Controller;

use App\Account\Application\Auth\Exception\AccountAlreadyRegisteredException;
use App\Account\Application\Auth\RegistrationProcess;
use App\Account\Presentation\Controller\RegisterController\RegisterRequestDTO;
use App\Account\Presentation\Controller\RegisterController\RegisterResponseDTO;
use App\Account\Presentation\Controller\RegisterController\RegisterResponseTransformer;
use App\Shared\Presentation\Exception\PresentationException;
use Local\HttpData\Attribute\MapBody;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/auth/register', methods: Request::METHOD_POST, stateless: true)]
final readonly class RegisterController
{
    public function __construct(
        private RegistrationProcess $registration,
        private RegisterResponseTransformer $response,
    ) {}

    public function __invoke(#[MapBody] RegisterRequestDTO $request): RegisterResponseDTO
    {
        try {
            return $this->response->transform($this->registration->register(
                login: $request->login,
                password: $request->password,
            ));
        } catch (AccountAlreadyRegisteredException $e) {
            throw PresentationException::fromApplicationException($e);
        }
    }
}
