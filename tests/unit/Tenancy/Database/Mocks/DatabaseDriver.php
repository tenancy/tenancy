<?php

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