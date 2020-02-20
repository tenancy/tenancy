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

namespace Tenancy\Tests\Hooks\Database;

use InvalidArgumentException;
use Tenancy\Hooks\Database\Events\Drivers\Creating;
use Tenancy\Hooks\Database\Provider;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\Concerns\InteractsWithDatabases;
use Tenancy\Testing\TestCase;

class DatabaseResolverTest extends TestCase
{
    use InteractsWithDatabases;

    protected $additionalProviders = [Provider::class];

    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();

        $this->resolveDatabase(function () {
            return new Mocks\NullDriver();
        });
    }

    /**
     * @test
     */
    public function can_use_connection()
    {
        $this->configureDatabase(function ($event) {
            $event->useConnection('sqlite', $event->configuration);
        });

        $this->events->listen(Creating::class, function (Creating $event) {
            $this->assertEquals(
                'sqlite',
                $event->configuration['driver']
            );
        });

        $this->events->dispatch(new Created($this->tenant));
    }

    /**
     * @test
     */
    public function can_use_config()
    {
        $this->configureDatabase(function ($event) {
            $event->useConfig(__DIR__.'/database.php', $event->configuration);
        });

        $this->events->listen(Creating::class, function (Creating $event) {
            $this->assertEquals(
                'sqlite',
                $event->configuration['driver']
            );
        });

        $this->events->dispatch(new Created($this->tenant));
    }

    /**
     * @test
     */
    public function use_config_detects_invalid_path()
    {
        $this->configureDatabase(function ($event) {
            $event->useConfig(__DIR__.'/arlon.php', $event->configuration);
        });

        $this->expectException(InvalidArgumentException::class);

        $this->events->dispatch(new Created($this->tenant));
    }
}
