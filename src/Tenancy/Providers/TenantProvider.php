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

namespace Tenancy\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Tenancy\Environment;
use Tenancy\Identification\Contracts\Tenant;

class TenantProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        $this->app->bind(Tenant::class, function () {
            /** @var Environment $env */
            $env = resolve(Environment::class);

            if ($env->isIdentified()) {
                return $env->getTenant();
            }

            return $env->identifyTenant();
        });
    }

    public function provides()
    {
        return [
            Tenant::class,
        ];
    }
}
