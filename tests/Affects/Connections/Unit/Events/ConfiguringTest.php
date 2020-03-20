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

namespace Tenancy\Tests\Affects\Connections\Unit\Events;

use InvalidArgumentException;
use Tenancy\Affects\Connections\Events\Drivers\Configuring;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Connections\ConnectionResolvingListener;
use Tenancy\Tests\UsesConnections;

class ConfiguringTest extends TestCase
{
    use UsesConnections;

    /** @test */
    public function use_connection_uses_registered_connections()
    {
        $config = [];

        $event = new Configuring($this->mockTenant(), $config, new ConnectionResolvingListener());

        $this->assertNotEquals(
            config('database.connections.mysql'),
            $config
        );

        $event->useConnection('mysql');

        $this->assertEquals(
            config('database.connections.mysql'),
            $config
        );
    }

    /** @test */
    public function use_connection_can_be_overriden()
    {
        $config = [];

        $event = new Configuring($this->mockTenant(), $config, new ConnectionResolvingListener());

        $event->useConnection('mysql', ['driver' => 'tenancy']);

        $this->assertNotEquals(
            config('database.connections.mysql'),
            $config
        );

        $this->assertEquals(
            'tenancy',
            $config['driver']
        );
    }

    /** @test */
    public function use_config_uses_the_provided_path()
    {
        $config = [];

        $event = new Configuring($this->mockTenant(), $config, new ConnectionResolvingListener());

        $loadedConfig = include $this->getSqliteConfigurationPath();

        $event->useConfig($this->getSqliteConfigurationPath());

        $this->assertEquals(
            $loadedConfig,
            $config
        );
    }

    /** @test */
    public function use_config_can_be_override()
    {
        $config = [];

        $event = new Configuring($this->mockTenant(), $config, new ConnectionResolvingListener());

        $event->useConfig($this->getSqliteConfigurationPath(), ['driver' => 'tenancy']);

        $this->assertEquals(
            'tenancy',
            $config['driver']
        );
    }

    /** @test */
    public function use_config_checks_if_the_file_exists()
    {
        $config = [];

        $event = new Configuring($this->mockTenant(), $config, new ConnectionResolvingListener());

        $this->expectException(InvalidArgumentException::class);

        $event->useConfig(__DIR__.'this_does_not_exist.php');
    }
}
