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

namespace Tenancy\Tests\Affects\Connections;

use InvalidArgumentException;
use Tenancy\Affects\Connections\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Concerns\InteractsWithConnections;
use Tenancy\Testing\TestCase;

class ConnectionResolverTest extends TestCase
{
    use InteractsWithConnections;
    protected $additionalProviders = [Provider::class];

    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
        $this->resolveTenant($this->tenant);

        $this->resolveConnection(function () {
            return new Mocks\ConnectionListener();
        });
    }

    /**
     * @test
     */
    public function can_use_connection()
    {
        $this->configureConnection(function ($event) {
            $event->useConnection('sqlite', $event->configuration);
        });

        Tenancy::identifyTenant();

        $this->assertEquals(
            'sqlite',
            config('database.connections.tenant.driver')
        );
    }

    /**
     * @test
     */
    public function can_use_config()
    {
        $this->configureConnection(function ($event) {
            $event->useConfig(__DIR__.'/database.php', $event->configuration);
        });

        Tenancy::identifyTenant();

        $this->assertEquals(
            'sqlite',
            config('database.connections.tenant.driver')
        );
    }

    /**
     * @test
     */
    public function use_config_detects_invalid_path()
    {
        $this->configureConnection(function ($event) {
            $event->useConfig(__DIR__.'/arlon.php', $event->configuration);
        });

        $this->expectException(InvalidArgumentException::class);

        Tenancy::identifyTenant();
    }
}
