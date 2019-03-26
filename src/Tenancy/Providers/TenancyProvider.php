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

use Illuminate\Support\ServiceProvider;
use Tenancy\Database\Contracts\ProvidesPassword;
use Tenancy\Database\PasswordGenerator;
use Tenancy\Environment;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\TenantResolver;

class TenancyProvider extends ServiceProvider
{
    use Provides\ProvidesBindings,
        Provides\ProvidesConfig,
        Provides\ProvidesEloquent,
        Provides\ProvidesListeners;

    public $singletons = [
        Environment::class => Environment::class,
        ResolvesTenants::class => TenantResolver::class,
        ProvidesPassword::class => PasswordGenerator::class,
    ];

    public function register()
    {
        $this->runTrait('register');

        $this->app->register(TenantProvider::class);
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

    public function provides()
    {
        return [
            Environment::class,
            ResolvesTenants::class,
        ];
    }
}
