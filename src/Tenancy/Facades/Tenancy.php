<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Facades;

use Illuminate\Support\Facades\Facade;
use Tenancy\Environment;

/**
 * @method static \Tenancy\Identification\Contracts\Tenant|null identifyTenant(bool $refresh = false, string $contract = null)
 * @method static bool isIdentified()
 * @method static \Tenancy\Identification\Contracts\Tenant|null getTenant()
 * @method static bool hasMacro(string $name)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static void setIdentified(bool $identified)
 * @method static void setTenant(\Tenancy\Identification\Contracts\Tenant $tenant = null)
 *
 * @see \Tenancy\Environment
 */
class Tenancy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Environment::class;
    }
}
