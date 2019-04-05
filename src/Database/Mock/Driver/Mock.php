<?php declare(strict_types=1);

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

namespace Tenancy\Database\Drivers\Mock\Driver;

use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Identification\Contracts\Tenant;

class Mock implements ProvidesDatabase
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
        $config = $this->configure($tenant);

        return true;
    }

    public function update(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        return true;
    }

    public function delete(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        return false;
    }
}
