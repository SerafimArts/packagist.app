<?php

declare(strict_types=1);

namespace App\Account\Presentation\Controller;

use App\Account\Application\Auth\AuthCommand;
use App\Account\Application\Auth\Exception\InvalidCredentialsException;
use App\Account\Domain\Token\Token;
use App\Account\Presentation\Controller\LoginController\LoginRequestDTO;
use App\Account\Presentation\Controller\LoginController\LoginResponseDTO;
use App\Account\Presentation\Controller\LoginController\LoginResponseTransformer;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Shared\Presentation\Exception\Http\HttpPresentationException;
use Local\HttpData\Attribute\MapBody;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/auth/login', name: 'auth.login', methods: Request::METHOD_POST, stateless: true)]
final readonly class LoginController
{
    public function __construct(
        private LoginResponseTransformer $response,
        private CommandBusInterface $commands,
    ) {}

    public function __invoke(#[MapBody] LoginRequestDTO $request): LoginResponseDTO
    {
        try {
            $result = $this->commands->send(new AuthCommand(
                login: $request->login,
                password: $request->password,
            ));

            if (!$result instanceof Token) {
                throw new HttpPresentationException(
                    message: 'An internal error occurred while processing the login process',
                );
            }

            return $this->response->transform($result);
        } catch (InvalidCredentialsException $e) {
            throw HttpPresentationException::fromApplicationException($e);
        }
    }
}
