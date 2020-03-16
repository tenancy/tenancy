<?php

namespace Tenancy\Tests\Mocks\Connections;

use Tenancy\Affects\Connections\Contracts\ProvidesConfiguration;
use Tenancy\Affects\Connections\Events\Drivers\Configuring;
use Tenancy\Affects\Connections\Events\Resolving;
use Tenancy\Concerns\DispatchesEvents;
use Tenancy\Identification\Contracts\Tenant;

class ConnectionResolvingListener implements ProvidesConfiguration
{
    use DispatchesEvents;

    public function handle(Resolving $event)
    {
        return $this;
    }

    public function configure(Tenant $tenant): array
    {
        $config = [];

        $this->events()->dispatch(new Configuring($tenant, $config, $this));

        return $config;
    }
}
