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

namespace Tenancy\Tests\Database\Mocks;

use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Identification\Contracts\Tenant;

class NullDriver implements ProvidesDatabase
{
    public function configure(Tenant $tenant): array
    {
        $config = [];

        $config['database'] = $tenant->getTenantKey();

        event(new Configuring($tenant, $config, $this));

        return $config;
    }

    public function create(Tenant $tenant): bool
    {
        $this->configure($tenant);

        return true;
    }

    public function update(Tenant $tenant): bool
    {
        $this->configure($tenant);

        return true;
    }

    public function delete(Tenant $tenant): bool
    {
        $this->configure($tenant);

        return true;
    }
}
