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

namespace Tenancy\Tests\Affects\Filesystem;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Filesystem\FilesystemManager;
use Tenancy\Affects\Filesystem\Events\ConfigureDisk;
use Tenancy\Affects\Filesystem\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConfiguresDiskTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /**
     * @var FilesystemManager|Factory
     */
    protected $manager;

    /**
     * @var Tenant
     */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->manager = $this->app->make(Factory::class);

        $this->events->listen(ConfigureDisk::class, function (ConfigureDisk $event) {
            $event->config['driver'] = 'local';
            $event->config['root'] = '/tmp/t-filesystem-' . $event->event->tenant->getTenantKey();
        });

        $this->tenant = $this->mockTenant();
    }

    /**
     * @test
     */
    public function configuration_initially_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Driver [] is not supported.');

        $this->manager->disk('tenant');
    }

    /**
     * @test
     */
    public function disk_valid_when_switched()
    {
        $this->resolveTenant($this->tenant);

        Tenancy::getTenant();

        $disk = $this->manager->disk('tenant');

        $disk->put('key', $this->tenant->getTenantKey());

        Tenancy::setTenant(null);

        try {
            $disk = $this->manager->disk('tenant');
        } catch (\InvalidArgumentException $e) {
        }

        Tenancy::setTenant($this->tenant);

        $disk = $this->manager->disk('tenant');

        $this->assertEquals($this->tenant->getTenantKey(), $disk->get('key'));
    }
}
