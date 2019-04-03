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

namespace Tenancy\Tests\Affects\Config;

use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;
use Tenancy\Testing\Mocks\Tenant;
use Illuminate\Support\Facades\Event;
use Illuminate\Contracts\Config\Repository;
use Tenancy\Identification\Events\Resolving;
use Tenancy\Affects\Config\Events\ConfigureConfig;
use Tenancy\Affects\Config\Providers\ServiceProvider;

class ConfiguresConfigTest extends TestCase
{
    protected $additionalProviders = [ServiceProvider::class];

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @test
     */
    public function is_instance_of_repository()
    {
        Event::listen(ConfigureConfig::class, function (ConfigureConfig $event) {
            $this->assertInstanceOf(Repository::class, $event->config);
        });

        $this->tenant = $this->mockTenant();
        Tenancy::setTenant($this->tenant);
    }

    /**
     * @test
     */
    public function can_set_config()
    {
        $this->assertNull(config('cool.test'));

        Event::listen(ConfigureConfig::class, function (ConfigureConfig $event) {
            $event->config->set('cool.test', true);
        });

        $this->tenant = $this->mockTenant();
        Tenancy::setTenant($this->tenant);

        $this->assertTrue(config('cool.test'));
    }
}
