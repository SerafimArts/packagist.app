<?php

declare(strict_types=1);

namespace Local\Integration\Github;

use League\OAuth2\Client\Provider\Github;
use Local\Integration\IntegrationBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal Local\Integration\Github
 */
final class GithubIntegrationBundle extends Bundle
{
    /**
     * @var non-empty-string
     */
    public const string CLIENT_KEY = 'github';

    public function build(ContainerBuilder $container): void
    {
        $container->register(Github::class)
            ->addArgument([
                'clientId' => '%env(OAUTH_GITHUB_ID)%',
                'clientSecret' => '%env(OAUTH_GITHUB_SECRET)%',
            ]);

        $container->register(GithubClient::class)
            ->setAutowired(true)
            ->addTag(IntegrationBundle::CLIENT_TAG_NAME, [
                IntegrationBundle::CLIENT_TAG_KEY => self::CLIENT_KEY,
            ]);
    }
}
