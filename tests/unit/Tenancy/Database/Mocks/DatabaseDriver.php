<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Database\Mocks;

use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Identification\Contracts\Tenant;

class DatabaseDriver implements ProvidesDatabase
{

    /**
     * @param Tenant $tenant
     * @return array
     */
    public function configure(Tenant $tenant): array
    {
        return [
            'driver' => 'sqlite',
            'database' => database_path("database-{$tenant->getTenantKey()}.sqlite"),
            'tenant-key' => $tenant->getTenantKey()
        ];
    }

    /**
     * @param Tenant $tenant
     * @return bool
     */
    public function create(Tenant $tenant): bool
    {
        return true;
    }

    /**
     * @param Tenant $tenant
     * @return bool
     */
    public function update(Tenant $tenant): bool
    {
        return true;
    }

    /**
     * @param Tenant $tenant
     * @return bool
     */
    public function delete(Tenant $tenant): bool
    {
        return true;
    }
}
