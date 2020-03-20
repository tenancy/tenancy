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

namespace Tenancy\Tests\Framework\Feature\Facades;

use Tenancy\Environment;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\TestCase;

class TenancyTest extends TestCase
{
    /** @var Tenant */
    private $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    /** @test */
    public function it_proxies_calls_to_the_environment()
    {
        $this->mock(Environment::class, function ($mock) {
            $mock
                ->shouldReceive('isIdentified');
        });
        Tenancy::isIdentified();
    }

    /** @test */
    public function it_can_proxy_environment_calls_right()
    {
        $this->assertNull(Tenancy::identifyTenant());

        $this->assertInstanceOf(Environment::class, Tenancy::setTenant($this->tenant));

        $this->assertEquals(
            $this->tenant->name,
            optional(Tenancy::identifyTenant())->name
        );
    }
}
