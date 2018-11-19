<?php

namespace Tenancy\Eloquent\Connection;

use Tenancy\Environment;

trait OnTenant
{
    public function getConnectionName()
    {
        return Environment::getTenantConnectionName();
    }
}
