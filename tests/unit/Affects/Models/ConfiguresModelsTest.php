<?php

namespace Tenancy\Tests\Affects\Models;

use Tenancy\Affects\Models\Events\ConfigureModels;
use Tenancy\Affects\Models\Providers\ServiceProvider;
use Tenancy\Environment;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConfiguresModelsTest extends TestCase
{
    protected $additionalProviders = [ServiceProvider::class];

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

        $this->expectExceptionMessage('Database [tenant] not configured.');
        (new Tenant())->getConnection();
    }
}