<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Providers;

use Illuminate\Support\ServiceProvider;
use Tenancy\Affects\AffectResolver;
use Tenancy\Affects\Contracts\ResolvesAffects;
use Tenancy\Database\Contracts\ProvidesPassword;
use Tenancy\Database\Contracts\ResolvesConnections;
use Tenancy\Database\DatabaseResolver;
use Tenancy\Database\Events as Database;
use Tenancy\Database\Hooks\DatabaseMutation;
use Tenancy\Database\Listeners as Listen;
use Tenancy\Database\PasswordGenerator;
use Tenancy\Environment;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Events\Switched;
use Tenancy\Identification\TenantResolver;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Lifecycle\HookResolver;
use Tenancy\Tenant\Events as Tenant;

class TenancyProvider extends ServiceProvider
{
    use Provides\ProvidesBindings,
        Provides\ProvidesConfig,
        Provides\ProvidesListeners,
        Provides\ProvidesHooks;

    protected $configs = [
        __DIR__.'/../resources/config/tenancy.php' => 'tenancy',
    ];

    public $singletons = [
        Environment::class         => Environment::class,
        ResolvesHooks::class       => HookResolver::class,
        ResolvesAffects::class     => AffectResolver::class,
        ResolvesTenants::class     => TenantResolver::class,
        ProvidesPassword::class    => PasswordGenerator::class,
        ResolvesConnections::class => DatabaseResolver::class,
    ];

    protected $hooks = [
        DatabaseMutation::class,
    ];

    protected $listen = [
        Tenant\Created::class => [
            ResolvesHooks::class,
        ],
        Tenant\Updated::class => [
            ResolvesHooks::class,
        ],
        Tenant\Deleted::class => [
            ResolvesHooks::class,
        ],
        Database\Resolved::class => [
            Listen\SetConnection::class,
        ],
        Switched::class => [
            ResolvesAffects::class,
        ],
    ];

    protected $subscribe = [
    ];

    public function register()
    {
        $this->runTrait('register');

        $this->app->register(TenantProvider::class);
    }

    public function boot()
    {
        $this->subscribe[] = resolve(ResolvesConnections::class);

        $this->runTrait('boot');
    }

    protected function runTrait(string $runtime)
    {
        $class = static::class;

        foreach (class_uses_recursive($class) as $trait) {
            if (method_exists($class, $method = $runtime.class_basename($trait))) {
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
