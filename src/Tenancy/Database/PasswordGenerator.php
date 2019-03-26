<?php

namespace Tenancy\Database;

use Tenancy\Database\Contracts\ProvidesPassword;
use Tenancy\Identification\Contracts\Tenant;

class PasswordGenerator implements ProvidesPassword
{
    /**
     * @param Tenant $tenant
     * @return string
     */
    public function generate(Tenant $tenant): string
    {
        return md5(sprintf(
            '%s-%s-%s',
            $tenant->getTenantIdentifier(),
            $tenant->getTenantKey(),
            config('tenancy.key') ?? config('app.key')
        ));
    }
}