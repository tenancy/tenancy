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

namespace Tenancy\Tests\Hooks\Database;

use Illuminate\Database\ConnectionInterface;
use InvalidArgumentException;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Database\Events\Drivers\Configuring;
use Tenancy\Hooks\Database\Events\Resolving;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class DatabaseResolverTest extends TestCase
{
    /**
     * @var Tenant
     */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->events->forget(Resolving::class);

        $this->resolveDatabase(function (Resolving $event) {
            return new Mocks\DatabaseDriver();
        });
        $this->resolveConnection(function ($event) {
            return new Mocks\ConnectionListener();
        });
        $this->configureConnection(function ($event) {
            $event->configuration = [
                'driver'     => 'sqlite',
                'database'   => database_path("database-{$event->tenant->getTenantKey()}.sqlite"),
                'tenant-key' => $event->tenant->getTenantKey(),
            ];
        });
        $this->resolveTenant($this->tenant = $this->mockTenant());
    }

    /**
     * @test
     */
    public function resolves_driver()
    {
        $this->events->dispatch(new Created($this->tenant));

        /* @var ConnectionInterface $connection */
        Tenancy::setTenant($this->tenant);
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
