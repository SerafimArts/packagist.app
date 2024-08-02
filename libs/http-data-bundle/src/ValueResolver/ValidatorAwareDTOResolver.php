<?php

declare(strict_types=1);

namespace Local\HttpData\ValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class ValidatorAwareDTOResolver implements ValueResolverInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private DTOResolver $resolver,
    ) {}

    /**
     * @return iterable<array-key, mixed>
     * @throws \Throwable
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        foreach ($this->resolver->resolve($request, $argument) as $result) {
            $errors = $this->validator->validate($result);

            if ($errors->count() > 0) {
                throw new ValidationFailedException($result, $errors);
            }

            yield $result;
        }

        return [];
    }
}
