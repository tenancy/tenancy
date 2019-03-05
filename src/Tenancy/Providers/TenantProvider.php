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

namespace Tenancy\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Contracts\Tenant;

class TenantProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        $this->app->bind(Tenant::class, function () {
            /** @var ResolvesTenants $resolver */
            $resolver = resolve(ResolvesTenants::class);

            return $resolver();
        });
    }

    public function provides()
    {
        return [
             Tenant::class
         ];
    }
}
