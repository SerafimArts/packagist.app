<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Persistence\Type;

use App\Account\Domain\Role;
use App\Shared\Infrastructure\Persistence\Type\StringEnumArrayType;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Account\Infrastructure\Persistence\Type
 */
final class AccountRoleListType extends StringEnumArrayType
{
    public function getName(): string
    {
        return 'account_roles';
    }

    protected static function getEnumClass(): string
    {
        return Role::class;
    }
}
