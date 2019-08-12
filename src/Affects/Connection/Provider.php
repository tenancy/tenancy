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

namespace Tenancy\Affects\Connection;

use Tenancy\Environment;
use Tenancy\Support\AffectsProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\DatabaseManager;
use Tenancy\Database\Events as Database;
use Tenancy\Providers\Provides\ProvidesBindings;
use Tenancy\Affects\Connection\Contracts\ResolvesConnections;

class Provider extends AffectsProvider
{
    use ProvidesBindings;

    protected $affects = [ConfiguresConnection::class];

    public $singletons = [
        ResolvesConnections::class => ConnectionResolver::class,
    ];

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Database\Resolved::class => [
            Listeners\SetConnection::class,
        ],
    ];

    public function boot(){
        Environment::macro('getTenantConnectionName', function(){
            return config('tenancy.database.tenant-connection-name', 'tenant');
        });

        Environment::macro('getTenantConnection', function(){
            return resolve(DatabaseManager::class)->connection(static::getTenantConnectionName());;
        });

        $this->ProvidesListeners();
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

    public function ProvidesListeners()
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
