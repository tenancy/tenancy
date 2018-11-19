<?php

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

use Illuminate\Support\ServiceProvider;
use Tenancy\Environment;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\TenantResolver;

class TenancyProvider extends ServiceProvider
{
    use Provides\ProvidesConfig,
        Provides\ProvidesEloquent,
        Provides\ProvidesListeners,
        Provides\ProvidesMiddleware,
        Provides\ProvidesMigrations;

    public $singletons = [
        Environment::class => Environment::class,
        ResolvesTenants::class => TenantResolver::class
    ];

    public function register()
    {
        $this->runTrait('register');
    }

    public function boot()
    {
        $this->runTrait('boot');
    }

    protected function runTrait(string $runtime)
    {
        $class = static::class;

        foreach (class_uses_recursive($class) as $trait) {
            if (method_exists($class, $method = $runtime . class_basename($trait))) {
                call_user_func([$this, $method]);
            }
        }
    }
}
