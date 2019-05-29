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

namespace Tenancy\Identification\Support;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Events\Switched;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Tenant\Events as Lifecycle;

abstract class DriverProvider extends EventServiceProvider
{
    /**
     * Listeners that affect the app logic when a tenant
     * is resolved or switched to.
     *
     * @var array
     */
    protected $affects = [];

    /**
     * Lifecycle event hooks. Hooks that run specific logic
     * during Tenant creation, updates or deletion.
     *
     * @var array
     */
    protected $hooks = [];

    /**
     * Identification driver registered by the Service Provider.
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * Configuration files to publish.
     *
     * @var array
     */
    protected $configs = [];

    public function register()
    {
        $this->app->resolving(ResolvesTenants::class, function (ResolvesTenants $resolver) {
            foreach ($this->drivers as $contract => $method) {
                $resolver->registerDriver($contract, $method);
            }
        });

        foreach ($this->configs as $config) {
            $configPath = basename($config);
            $configName = basename($config, '.php');

            $this->publishes([$config => config_path('tenancy\\' . $configPath)], [$configName, "tenancy"]);

            $this->mergeConfigFrom($config, 'tenancy.' . $configName);
        }

        foreach ($this->affects as $affect) {
            Event::listen(Switched::class, $affect);
        }

        $this->app->resolving(ResolvesHooks::class, function (ResolvesHooks $resolver) {
            foreach ($this->hooks as $hook) {
                $resolver->addHook($hook);
            }
        });
    }
}
