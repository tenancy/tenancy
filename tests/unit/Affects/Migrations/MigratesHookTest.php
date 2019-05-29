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

use Illuminate\Support\Facades\DB;
use Tenancy\Affects\Migrations\Events\ConfigureMigrations;
use Tenancy\Affects\Migrations\Providers\ServiceProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;

class MigratesHookTest extends TestCase
{
    protected $additionalProviders = [ServiceProvider::class];

    /**
     * @test
     */
    public function migrates()
    {
        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) {
            $event->path(__DIR__ . '/database/');
        });

        $this->resolveTenant($tenant = $this->createMockTenant());
        Tenancy::getTenant();

        DB::connection(Tenancy::getTenantConnectionName())
            ->table('mocks')
            ->get();
    }
}