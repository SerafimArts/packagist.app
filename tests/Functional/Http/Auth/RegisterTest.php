<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Auth;

use App\Tests\Functional\Http\HttpTestCase;
use PHPUnit\Framework\Attributes\TestDox;

#[TestDox('POST /auth/register')]
final class RegisterTest extends HttpTestCase
{
    public function testWithoutBody(): void
    {
        $this->json('POST', '/auth/register')
            ->assertStatusUnprocessable()
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.v1.json')
            ->assertJsonPathSame('$.error', 'Missing required [login] property');
    }

    public function testOnlyLogin(): void
    {
        $this->json('POST', '/auth/register', body: [
            'login' => __FUNCTION__
        ])
            ->assertStatusUnprocessable()
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.v1.json')
            ->assertJsonPathSame('$.error', 'Missing required [password] property');
    }

    public function testOnlyPassword(): void
    {
        $this->json('POST', '/auth/register', body: [
            'password' => __FUNCTION__
        ])
            ->assertStatusUnprocessable()
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.v1.json')
            ->assertJsonPathSame('$.error', 'Missing required [login] property');
    }

    public function testSuccessfulRegistration(): void
    {
        $this->json('POST', '/auth/register', body: [
            'login' => __FUNCTION__,
            'password' => __FUNCTION__,
        ])
            ->assertStatusOk()
            ->assertJsonSchemaFileMatches(__DIR__ . '/register.json')
            ->assertJsonPathSame('$.data.account.login', __FUNCTION__);
    }
}
