<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Affects\Models;

use InvalidArgumentException;
use Tenancy\Affects\Connection\Provider as ConnectionProvider;
use Tenancy\Affects\Models\Events\ConfigureModels;
use Tenancy\Affects\Models\Provider as ModelsProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConfigureModelsTest extends TestCase
{
    protected $additionalProviders = [ConnectionProvider::class, ModelsProvider::class];

    /**
     * @var Tenant
     */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    /**
     * @test
     */
    public function can_override_connection_resolver()
    {
        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) {
            $event->staticCallOnModels(
                [Mocks\TenantModel::class],
                'setConnectionResolver',
                [new Mocks\ConnectionResolver(Tenancy::getTenantConnectionName(), resolve('db'))]);
        });

        // This should not trigger an Exception, because it is using the default app connection.
        (new Mocks\TenantModel())->getConnection();

        $this->resolveTenant($this->tenant);
        Tenancy::getTenant();

        $this->assertEquals(Mocks\ConnectionResolver::class, get_class(Mocks\TenantModel::getConnectionResolver()));

        $this->expectExceptionMessage('Database [tenant] not configured.');
        (new Mocks\TenantModel())->getConnection();
    }

    /**
     * @test
     */
    public function detects_wrong_classes()
    {
        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) {
            $event->staticCallOnModels(
                [Mocks\FakeModel::class],
                'setConnectionResolver',
                [new Mocks\ConnectionResolver(Tenancy::getTenantConnectionName(), resolve('db'))]);
        });

        $this->expectException(InvalidArgumentException::class);

        $this->resolveTenant($this->tenant);
        Tenancy::getTenant();
    }

    /**
     * @test
     */
    public function can_override_builder()
    {
        $this->artisan('migrate', [
            '--path'     => realpath(__DIR__.'/migrations/'),
            '--realpath' => true,
        ]);

        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) {
            $event->staticCallOnModels(
                [Mocks\TenantModel::class],
                'addGlobalScope',
                [new Mocks\TenantScope()]);
            $event->staticCallOnModels(
                [Mocks\TenantModel::class],
                'creating',
                [function($model){
                    if(!isset($model->tenant_id)){
                        $model->tenant_id = Tenancy::getTenant()->getTenantKey();
                    }
                }]
            );
        });

        (new Mocks\TenantModel())->create();

        $this->resolveTenant($this->tenant);
        Tenancy::getTenant();

        // When on tenant model should not return any models
        $this->assertEmpty(
            Mocks\TenantModel::all()
        );

        (new Mocks\TenantModel())->create();

        // Should return the models on tenat
        foreach (Mocks\TenantModel::all() as $model) {
            $this->assertEquals(
                Tenancy::getTenant()->getTenantKey(),
                $model->tenant_id
            );
        }

        Tenancy::setTenant(null);
        foreach (Mocks\TenantModel::all() as $model) {
            $this->assertNull($model->tenant_id);
        }
    }
}
