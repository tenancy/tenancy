<?php declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Affects\Migrations;

use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Tenancy\Tenant\Events\Created;
use Tenancy\Hooks\Migrations\Providers\ServiceProvider;
use Tenancy\Hooks\Migrations\Events\ConfigureMigrations;
use Tenancy\Database\Drivers\Sqlite\Providers\ServiceProvider as DatabaseProvider;

class ConfiguresMigrationsTest extends TestCase
{
    protected $additionalProviders = [DatabaseProvider::class, ServiceProvider::class];
    /**
     * @var \Tenancy\Testing\Mocks\Tenant
     */
    protected $tenant;

    public function afterSetUp()
    {
        $this->resolveTenant($this->tenant = $this->mockTenant([
            'id' => 3607
        ]));

        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) {
            $event->path(__DIR__ . '/database/');
        });
    }

    /**
     * @test
     */
    public function can_disable()
    {
        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) {
            $event->disable();
        });

        $this->events->dispatch(new Created($this->tenant));

        $this->assertFalse(
            DB::connection(Tenancy::getTenantConnectionName())
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );
    }
}
