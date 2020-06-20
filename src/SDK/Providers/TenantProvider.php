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

namespace Tenancy\SDK\Provider;

use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Support\Provider;

class TenantProvider extends Provider
{
    /**
     * Registers the provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving(ResolvesTenants::class, function (ResolvesTenants $resolver) {
            foreach (config('tenancy.sdk.identification.tenants') as $tenant) {
                $resolver->addModel($tenant);
            }

            return $tenant;
        });
    }
}
