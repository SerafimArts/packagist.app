<?php

declare(strict_types=1);

namespace Local\Integration\Github;

use League\OAuth2\Client\Provider\Exception\GithubIdentityProviderException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Local\Integration\ClientInterface;
use Local\Integration\Exception\InvalidCodeException;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * @api
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal Local\Integration\Github
 */
final readonly class GithubClient implements ClientInterface
{
    /**
     * @var list<non-empty-string>
     */
    public const array DEFAULT_SCOPES = [
        'user',
        'repo',
        'write:repo_hook',
        'read:repo_hook',
    ];

    /**
     * @param list<non-empty-string> $scopes
     */
    public function __construct(
        private Github $provider,
        private UriFactoryInterface $uri,
        private array $scopes = self::DEFAULT_SCOPES,
    ) {}

    /**
     * @return list<non-empty-string>
     */
    private function getTokenScopes(): array
    {
        return $this->scopes;
    }

    private function getTokenScopesAsString(): string
    {
        return \implode(', ', $this->getTokenScopes());
    }

    /**
     * @return array<non-empty-string, string>
     */
    private function getOptions(): array
    {
        return [
            'scope' => $this->getTokenScopesAsString(),
        ];
    }

    public function getUri(): UriInterface
    {
        return $this->uri->createUri($this->provider->getAuthorizationUrl(
            options: $this->getOptions(),
        ));
    }

    /**
     * @param non-empty-string $code
     * @throws IdentityProviderException
     */
    private function getToken(string $code): AccessTokenInterface
    {
        return $this->provider->getAccessToken('authorization_code', [
            'code' => $code,
        ]);
    }

    public function getAccount(string $code): GithubAccount
    {
        try {
            $token = $this->getToken($code);
        } catch (GithubIdentityProviderException $e) {
            throw InvalidCodeException::becauseInvalidCode($code, $e);
        }

        $owner = $this->provider->getResourceOwner($token);

        $data = $owner->toArray();

        return new GithubAccount(
            id: (string) $owner->getId(),
            login: self::fetchNonEmptyStringOrNull($data, 'login'),
            email: self::fetchNonEmptyStringOrNull($data, 'email'),
            avatar: self::fetchNonEmptyStringOrNull($data, 'avatar_url'),
            attributes: $data,
        );
    }

    /**
     * @param array<array-key, mixed> $data
     * @param non-empty-string $key
     * @return non-empty-string|null
     */
    private static function fetchNonEmptyStringOrNull(array $data, string $key): ?string
    {
        $payload = $data[$key] ?? null;

        if (\is_scalar($payload) && $payload !== '') {
            return (string) $payload;
        }

        return null;
    }
}
