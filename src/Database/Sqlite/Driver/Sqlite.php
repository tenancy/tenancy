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

namespace Tenancy\Database\Drivers\Sqlite\Driver;

use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Hooks\Database\Events\Drivers as Events;
use Tenancy\Identification\Contracts\Tenant;

class Sqlite implements ProvidesDatabase
{
    public function configure(Tenant $tenant): array
    {
        $config = [];

        event(new Events\Configuring($tenant, $config, $this));

        return $config;
    }

    public function create(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        event(new Events\Creating($tenant, $config, $this));

        $result = touch($config['database']);

        event(new Events\Created($tenant, $this, $result));

        return $result;
    }

    public function update(Tenant $tenant): bool
    {
        $original = $tenant->newInstance($tenant->getOriginal());

        $previous = $this->configure($original);

        $config = $this->configure($tenant);

        event(new Events\Updating($tenant, $config, $this));

        $result = false;

        if ($previous['database'] !== $config['database']) {
            $result = rename(
                $previous['database'],
                $config['database']
            );
        }

        event(new Events\Updated($tenant, $this, $result));

        return $result;
    }

    public function delete(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        event(new Events\Deleting($tenant, $config, $this));

        $result = unlink($config['database']);

        event(new Events\Deleted($tenant, $this, $result));

        return $result;
    }
}
