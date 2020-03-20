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

namespace Tenancy\Tests\Framework\Unit\Providers;

use Tenancy\Providers\TenancyProvider;
use Tenancy\Testing\TestCase;

class TenancyProviderTest extends TestCase
{
    /** @var TenancyProvider */
    private $provider;

    protected function afterSetUp()
    {
        $this->provider = new TenancyProvider($this->app);
    }

    /** @test */
    public function all_singletons_are_registered()
    {
        foreach ($this->provider->singletons as $abstract => $class) {
            $this->assertInstanceOf(
                $class,
                $this->app->make($abstract)
            );
        }
    }

    /** @test */
    public function all_provides_are_registered()
    {
        foreach ($this->provider->provides() as $abstract) {
            $this->assertTrue($this->app->bound($abstract));
        }
    }
}
