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

namespace Tenancy\Testing\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Events\Resolving;
use Tenancy\Testing\Mocks\Factory\TenantFactory;
use Tenancy\Testing\Mocks\Tenant as Mock;

trait InteractsWithTenants
{
    protected function createMockTenant(array $attributes = [])
    {
        return Mock::factory()->create($attributes);
    }

    protected function mockTenant(array $attributes = []): Mock
    {
        return Mock::factory()->make($attributes);
    }

    protected function resolveTenant(Tenant $tenant = null)
    {
        $this->events->listen(Resolving::class, function (Resolving $event) use ($tenant) {
            return $tenant;
        });
    }

    protected function bootFactories()
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            if(is_subclass_of((new $modelName), Tenant::class)) {
                return TenantFactory::class;
            }

            $modelName = Str::startsWith($modelName, 'App\\Models\\')
                ? Str::after($modelName, 'App\\Models\\')
                : Str::after($modelName, 'App\\');

            return self::$namespace.$modelName.'Factory';
        });
    }
}
