<?php

declare(strict_types=1);

namespace App\Account\Presentation\Controller;

use App\Account\Application\Registration\Exception\AccountAlreadyRegisteredException;
use App\Account\Application\Registration\RegisterCommand;
use App\Account\Domain\Token\Token;
use App\Account\Presentation\Controller\RegisterController\RegisterRequestDTO;
use App\Account\Presentation\Controller\RegisterController\RegisterResponseDTO;
use App\Account\Presentation\Controller\RegisterController\RegisterResponseTransformer;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Shared\Presentation\Exception\Http\HttpPresentationException;
use Local\HttpData\Attribute\MapBody;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/auth/register', name: 'auth.register', methods: Request::METHOD_POST, stateless: true)]
final readonly class RegisterController
{
    public function __construct(
        private RegisterResponseTransformer $response,
        private CommandBusInterface $commands,
    ) {}

    public function __invoke(#[MapBody] RegisterRequestDTO $request): RegisterResponseDTO
    {
        try {
            $result = $this->commands->send(new RegisterCommand(
                login: $request->login,
                password: $request->password,
            ));

            if (!$result instanceof Token) {
                throw new HttpPresentationException(
                    message: 'An internal error occurred while processing the registration process',
                );
            }

            return $this->response->transform($result);
        } catch (AccountAlreadyRegisteredException $e) {
            throw HttpPresentationException::fromApplicationException($e);
        }
    }
}
