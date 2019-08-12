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

namespace Tenancy\Tests\Database;

use Illuminate\Database\ConnectionInterface;
use InvalidArgumentException;
use Tenancy\Database\Contracts\ResolvesConnections;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Database\Events\Resolving;
use Tenancy\Facades\Tenancy;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Hooks\Database\Provider;

class DatabaseResolverTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /**
     * @var ResolvesConnections
     */
    protected $resolver;
    /**
     * @var Tenant
     */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->events->forget(Resolving::class);

        $this->events->listen(Resolving::class, function (Resolving $event) {
            return new Mocks\DatabaseDriver();
        });
        $this->resolveTenant($this->tenant = $this->mockTenant());
    }

    /**
     * @test
     */
    public function resolves_driver()
    {
        $this->events->dispatch(new Created($this->tenant));

        /** @var ConnectionInterface $connection */
        $connection = Tenancy::getTenantConnection();

        $this->assertNotNull($connection);

        $this->assertEquals(Tenancy::getTenantConnectionName(), $connection->getConfig('name'));
        $this->assertEquals($this->tenant->getTenantKey(), $connection->getConfig('tenant-key'));
    }

    /**
     * @test
     */
    public function error_on_wrong_file()
    {
        $this->events->listen(Configuring::class, function (Configuring $event) {
            $event->useConfig('arlon.php');
        });

        $this->expectException(InvalidArgumentException::class);

        $this->events->dispatch(new Created($this->tenant));
    }
}
