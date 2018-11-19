<?php

namespace Tenancy\Eloquent\Connection;

use Tenancy\Environment;
use Tenancy\Identification\Contracts\Tenant;

trait OnSystem
{
    public function getConnectionName()
    {
        /** @var Tenant $tenant */
        $tenant = app(Tenant::class);
        return $tenant ? $tenant->getManagingSystemConnection() : Environment::getDefaultSystemConnectionName();
    }
}
