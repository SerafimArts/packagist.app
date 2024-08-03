<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http\Auth;

use App\Account\Application\Auth\RegistrationProcess;
use App\Tests\Functional\Http\HttpTestCase;

final class LoginTest extends HttpTestCase
{
    public function testWithoutBody(): void
    {
        $this->json('POST', '/auth/login')
            ->assertStatusUnprocessable()
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.v1.json')
            ->assertJsonPathSame('$.error', 'Missing required [login] property');
    }

    public function testOnlyLogin(): void
    {
        $this->json('POST', '/auth/login', body: [
            'login' => __FUNCTION__
        ])
            ->assertStatusUnprocessable()
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.v1.json')
            ->assertJsonPathSame('$.error', 'Missing required [password] property');
    }

    public function testOnlyPassword(): void
    {
        $this->json('POST', '/auth/login', body: [
            'password' => __FUNCTION__
        ])
            ->assertStatusUnprocessable()
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.v1.json')
            ->assertJsonPathSame('$.error', 'Missing required [login] property');
    }

    public function testInvalidLoginOrPassword(): void
    {
        $this->json('POST', '/auth/login', body: [
            'login' => __FUNCTION__,
            'password' => __FUNCTION__,
        ])
            ->assertStatusUnprocessable()
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.v1.json')
            ->assertJsonPathMatches('$.error', '/Invalid account "\w+" credentials/');
    }

    public function testInvalidPassword(): void
    {
        $process = $this->get(RegistrationProcess::class);
        $process->register(__FUNCTION__, '');

        $this->json('POST', '/auth/login', body: [
            'login' => __FUNCTION__,
            'password' => __FUNCTION__,
        ])
            ->assertStatusUnprocessable()
            ->assertJsonSchemaFileMatches(__DIR__ . '/../error.v1.json')
            ->assertJsonPathMatches('$.error', '/Invalid account "\w+" credentials/');
    }

    public function testSuccessfulLogin(): void
    {
        $process = $this->get(RegistrationProcess::class);
        $process->register(__FUNCTION__, __FUNCTION__);

        $this->json('POST', '/auth/login', body: [
            'login' => __FUNCTION__,
            'password' => __FUNCTION__,
        ])
            ->assertStatusOk()
            ->assertJsonSchemaFileMatches(__DIR__ . '/login.json')
            ->assertJsonPathSame('$.data.account.login', __FUNCTION__);
    }
}
