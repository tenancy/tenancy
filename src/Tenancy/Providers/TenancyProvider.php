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

use Illuminate\Support\ServiceProvider;
use Tenancy\Affects\AffectResolver;
use Tenancy\Affects\Contracts\ResolvesAffects;
use Tenancy\Environment;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Events\Switched;
use Tenancy\Identification\TenantResolver;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Lifecycle\HookResolver;
use Tenancy\Support\Contracts\ProvidesPassword;
use Tenancy\Support\PasswordGenerator;
use Tenancy\Tenant\Events as Event;

class TenancyProvider extends ServiceProvider
{
    use Provides\ProvidesListeners;

    public $singletons = [
        Environment::class         => Environment::class,
        ResolvesHooks::class       => HookResolver::class,
        ResolvesAffects::class     => AffectResolver::class,
        ResolvesTenants::class     => TenantResolver::class,
        ProvidesPassword::class    => PasswordGenerator::class,
    ];

    protected $listen = [
        Event\Created::class => [
            ResolvesHooks::class,
        ],
        Event\Updated::class => [
            ResolvesHooks::class,
        ],
        Event\Deleted::class => [
            ResolvesHooks::class,
        ],
        Switched::class => [
            ResolvesAffects::class,
        ],
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
