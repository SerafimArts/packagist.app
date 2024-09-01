<?php

declare(strict_types=1);

namespace App\Package\Domain\Name;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class NameValidator
{
    /**
     * @var non-empty-string
     */
    private const string PCRE_VENDOR = '[a-z0-9]([_.-]?[a-z0-9]+)*';

    /**
     * @var non-empty-string
     */
    private const string PCRE_NAME = '[a-z0-9](([_.]?|-{0,2})[a-z0-9]+)*';

    /**
     * @var non-empty-string
     */
    private const string PCRE_PACKAGE = self::PCRE_VENDOR . '/' . self::PCRE_NAME;

    public function __construct(
        private ValidatorInterface $validator,
    ) {}

    private static function pcre(string $pattern): string
    {
        return \sprintf('#^%s$#isu', $pattern);
    }

    public function getVendorError(string $vendor): ?ValidationFailedException
    {
        $violations = $this->validator->validate($vendor, [
            new Regex(self::pcre(self::PCRE_VENDOR), match: true),
        ]);

        if ($violations->count() > 0) {
            return new ValidationFailedException($vendor, $violations);
        }

        return null;
    }

    public function getNameError(string $name): ?ValidationFailedException
    {
        $violations = $this->validator->validate($name, [
            new Regex(self::pcre(self::PCRE_NAME), match: true),
        ]);

        if ($violations->count() > 0) {
            return new ValidationFailedException($name, $violations);
        }

        return null;
    }

    public function getPackageError(string $package): ?ValidationFailedException
    {
        $violations = $this->validator->validate($package, [
            new Regex(self::pcre(self::PCRE_PACKAGE), match: true),
        ]);

        if ($violations->count() > 0) {
            return new ValidationFailedException($package, $violations);
        }

        return null;
    }
}
