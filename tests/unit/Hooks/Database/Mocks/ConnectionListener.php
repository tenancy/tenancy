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

namespace Tenancy\Tests\Hooks\Database\Mocks;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Affects\Connection\Events\Drivers\Configuring;
use Tenancy\Affects\Connection\Contracts\ProvidesConfiguration;
use Tenancy\Concerns\DispatchesEvents;

class ConnectionListener implements ProvidesConfiguration
{
    use DispatchesEvents;

    public function configure(Tenant $tenant): array{
        $config = [];

        $this->events()->dispatch(new Configuring($tenant, $config, $this));

        return $config;
    }
}
