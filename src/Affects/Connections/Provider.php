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

namespace Tenancy\Affects\Connections;

use Illuminate\Database\DatabaseManager;
use Tenancy\Affects\Connections\Contracts\ResolvesConnections;
use Tenancy\Environment;
use Tenancy\Providers\Provides\ProvidesConfigs;
use Tenancy\Providers\Provides\ProvidesListeners;
use Tenancy\Support\AffectsProvider;

class Provider extends AffectsProvider
{
    use ProvidesConfigs;
    use ProvidesListeners;

    protected $configs = [
        __DIR__.'/resources/config/connections.php',
    ];

    protected $affects = [ConfiguresConnection::class];

    public $singletons = [
        ResolvesConnections::class => ConnectionResolver::class,
    ];

    public $listen = [
        Events\Resolved::class => [
            Listeners\SetConnection::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        Environment::macro('getTenantConnectionName', function () {
            return config('tenancy.connections.tenant-connection-name') ?? 'tenant';
        });
        Environment::macro('getTenantConnection', function () {
            return resolve(DatabaseManager::class)->connection(static::getTenantConnectionName());
        });
    }
}
