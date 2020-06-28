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

namespace Tenancy\Tests\Misc\Help\Feature;

use Tenancy\Misc\Help\Contracts\ResolvesPackages;
use Tenancy\Misc\Help\Provider;
use Tenancy\Testing\TestCase;

class ProviderTest extends TestCase
{
    /** @test */
    public function it_registers_the_package_resolver()
    {
        $this->assertFalse($this->app->bound(ResolvesPackages::class));

        $this->app->register(Provider::class);

        $this->assertTrue($this->app->bound(ResolvesPackages::class));
    }

    /** @test */
    public function it_registers_packages_into_the_resolver()
    {
        $this->app->register(Provider::class);

        $this->assertNotEmpty(
            $this->app->make(ResolvesPackages::class)->getPackages()
        );
    }
}
