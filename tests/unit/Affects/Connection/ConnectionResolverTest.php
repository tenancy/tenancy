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

namespace Tenancy\Tests\Affects\Connection;

use Tenancy\Testing\TestCase;
use Tenancy\Affects\Connection\Provider;
use Tenancy\Affects\Connection\Support\InteractsWithConnections;
use Tenancy\Facades\Tenancy;

class ConnectionResolverTest extends TestCase{
    use InteractsWithConnections;
    protected $additionalProviders = [Provider::class];

    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    /**
     * @test
     */
    public function simple_resolve(){
        $this->resolveConnection(function(){
            return new Mocks\ConnectionListener();
        });

        $this->configureConnection(function($event){
            $event->useConnection('sqlite', $event->configuration);
        });

        $this->resolveTenant($this->tenant);
        Tenancy::getTenant();

        $this->assertEquals(
            $this->tenant->getTenantKey(),
            config('database.connections.tenant.username')
        );
    }
}
