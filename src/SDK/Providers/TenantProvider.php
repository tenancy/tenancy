<?php

namespace Tenancy\SDK\Provider;

use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Support\Provider;

class TenantProvider extends Provider
{
    /**
     * Registers the provider
     *
     * @return void
     */
    public function register() {
        $this->app->resolving(ResolvesTenants::class, function (ResolvesTenants $resolver) {
            foreach (config('tenancy.sdk.identification.tenants') as $tenant) {
                $resolver->addModel($tenant);
            }

            return $tenant;
        });
    }
}
