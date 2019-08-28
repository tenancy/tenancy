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

use Tenancy\Facades\Tenancy;
use InvalidArgumentException;
use Tenancy\Testing\TestCase;
use Tenancy\Tenant\Events\Created;
use Tenancy\Hooks\Database\Provider;
use Tenancy\Hooks\Database\Events\Drivers\Creating;
use Tenancy\Hooks\Database\Support\InteractsWithDatabases;

class DatabaseResolverTest extends TestCase
{
    use InteractsWithDatabases;

    protected $additionalProviders = [Provider::class];

    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    /**
     * @test
     */
    public function can_use_connection()
    {
        $this->resolveDatabase(function () {
            return new Mocks\NullDriver();
        });

        $this->configureDatabase(function ($event) {
            $event->useConnection('sqlite', $event->configuration);
        });

        $this->events->listen(Creating::class, function(Creating $event){
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
        $this->resolveDatabase(function () {
            return new Mocks\NullDriver();
        });

        $this->configureDatabase(function ($event) {
            $event->useConfig(__DIR__.'/database.php', $event->configuration);
        });

        $this->events->listen(Creating::class, function(Creating $event){
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
        $this->resolveDatabase(function () {
            return new Mocks\NullDriver();
        });

        $this->configureDatabase(function ($event) {
            $event->useConfig(__DIR__.'/arlon.php', $event->configuration);
        });

        $this->expectException(InvalidArgumentException::class);

        $this->events->dispatch(new Created($this->tenant));
    }
}
