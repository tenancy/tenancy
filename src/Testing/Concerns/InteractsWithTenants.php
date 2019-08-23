<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Testing\Concerns;

use Closure;
use Tenancy\Affects\Connection\Events as Connection;
use Tenancy\Hooks\Database\Events as Database;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Events\Resolving;
use Tenancy\Testing\Mocks\Tenant as Mock;

trait InteractsWithTenants
{
    protected function createMockTenant(array $attributes = [])
    {
        return factory(Mock::class)->create($attributes);
    }

    protected function mockTenant(array $attributes = []): Mock
    {
        return factory(Mock::class)->make($attributes);
    }

    protected function resolveTenant(Tenant $tenant = null)
    {
        $this->events->listen(Resolving::class, function (Resolving $event) use ($tenant) {
            return $tenant;
        });
    }

    protected function resolveConnection(Closure $callback)
    {
        $this->events->listen(Connection\Resolving::class, $callback);
    }

    protected function resolveDatabase(Closure $callback)
    {
        $this->events->listen(Database\Resolving::class, $callback);
    }

    protected function configureConnection(Closure $callback)
    {
        $this->events->listen(Connection\Drivers\Configuring::class, $callback);
    }

    protected function configureDatabase(Closure $callback)
    {
        $this->events->listen(Database\Drivers\Configuring::class, $callback);
    }
}
