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

namespace Tenancy\Tests\Affects\Config;

use Tenancy\Affects\Connection\Provider;
use Tenancy\Environment;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class RegisterFunctionsTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

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
    public function registers_get_tenant_connection_name(){
        $this->assertEquals(
            'tenant',
            Environment::getTenantConnectionName()
        );
    }

    /**
     * @test
     */
    public function get_tenant_connection_name_prefers_config(){
        config(['tenancy.connection.tenant-connection-name' => 'tenant2']);

        $this->assertEquals(
            'tenant2',
            Environment::getTenantConnectionName()
        );
    }

    /**
     * @test
     */
    public function registers_get_tenant_connection(){
        $this->expectException(\InvalidArgumentException::class);
        Environment::getTenantConnection();
    }
}
