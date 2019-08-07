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

namespace Tenancy\Tests\Affects\Config;

use Tenancy\Affects\Config\Events\ConfigureConfig;
use Tenancy\Affects\Config\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConfiguresConfigTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /**
     * @var Tenant
     */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    /**
     * @test
     */
    public function can_set_config()
    {
        $this->assertNull(config('test'));

        $this->events->listen(ConfigureConfig::class, function (ConfigureConfig $event) {
            $event->config->set('test', true);
        });

        Tenancy::setTenant($this->tenant);

        $this->assertTrue(config('test'));
    }

    /**
     * @test
     */
    public function can_use_direct_call()
    {
        $this->assertNull(config('test'));

        $this->events->listen(ConfigureConfig::class, function (ConfigureConfig $event) {
            $event->set('test', true);
        });

        Tenancy::setTenant($this->tenant);

        $this->assertTrue(config('test'));
    }
}
