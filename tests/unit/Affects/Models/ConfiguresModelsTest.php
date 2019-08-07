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

namespace Tenancy\Tests\Affects\Models;

use Illuminate\Database\DatabaseManager;
use InvalidArgumentException;
use Tenancy\Affects\Models\Database\ConnectionResolver;
use Tenancy\Affects\Models\Events\ConfigureModels;
use Tenancy\Affects\Models\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Affects\Models\Mocks\ExtraResolver;

class ConfiguresModelsTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /**
     * @test
     */
    public function sets_model_connection()
    {
        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) {
            $event->onTenant([Tenant::class]);
        });

        // This should not trigger an Exception, because it is using the default app connection.
        (new Tenant())->getConnection();

        $this->resolveTenant($this->mockTenant());
        Tenancy::getTenant();

        $this->assertEquals(ConnectionResolver::class, get_class(Tenant::getConnectionResolver()));

        $this->expectExceptionMessage('Database [tenant] not configured.');
        (new Tenant())->getConnection();
    }

    /**
     * @test
     */
    public function resets_model_connection()
    {
        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) {
            $event->onTenant([Tenant::class]);
        });

        $this->resolveTenant($this->mockTenant());
        // Sets the connection to tenant.
        Tenancy::getTenant();

        Tenancy::setTenant(null);

        $this->assertEquals(DatabaseManager::class, get_class(Tenant::getConnectionResolver()));

        (new Tenant())->getConnection();
    }

    /**
     * @test
     */
    public function disables_resetting_model_connection()
    {
        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) {
            $event->onTenant([Tenant::class], false);
        });

        $this->resolveTenant($this->mockTenant());
        Tenancy::getTenant();

        $this->resolveTenant();
        Tenancy::getTenant(true);

        $this->assertEquals(ConnectionResolver::class, get_class(Tenant::getConnectionResolver()));

        $this->expectExceptionMessage('Database [tenant] not configured.');
        (new Tenant())->getConnection();
    }

    /**
     * @test
     */
    public function throws_error_on_invalid()
    {
        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) {
            $event->onTenant([Model::class], false);
        });

        $this->expectException(InvalidArgumentException::class);

        $this->resolveTenant($this->mockTenant());
        Tenancy::getTenant();
    }

    /**
     * @test
     */
    public function allows_resolver_overriding()
    {
        $resolver = new ExtraResolver(Tenancy::getTenantConnectionName(), resolve(DatabaseManager::class));

        ConfigureModels::$resolver = $resolver;

        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) use ($resolver) {
            $event->onTenant([Tenant::class]);
        });

        // This should not trigger an Exception, because it is using the default app connection.
        (new Tenant())->getConnection();

        $this->resolveTenant($this->mockTenant());
        Tenancy::getTenant();

        $this->assertEquals(get_class($resolver), get_class(Tenant::getConnectionResolver()));
    }
}
