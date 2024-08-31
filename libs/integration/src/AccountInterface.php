<?php

declare(strict_types=1);

namespace Local\Integration;

interface AccountInterface
{
    /**
     * The unique identifier for the user.
     *
     * @var non-empty-string
     */
    public string $id { get; }

    /**
     * An optional user's nickname / username / login.
     *
     * @var non-empty-string|null
     */
    public ?string $login { get; }

    /**
     * An optional user's e-mail address.
     *
     * @var non-empty-string|null
     */
    public ?string $email { get; }

    /**
     * An optional user's avatar image URL.
     *
     * @var non-empty-string|null
     */
    public ?string $avatar { get; }

    /**
     * Provides vendor-specific attributes
     *
     * @var array<non-empty-string, mixed>
     */
    public array $attributes { get; }
}
