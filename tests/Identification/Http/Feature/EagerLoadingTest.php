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

namespace Tenancy\Tests\Identification\Http\Feature;

use Illuminate\Contracts\Http\Kernel;
use Tenancy\Identification\Drivers\Http\Middleware\EagerIdentification;
use Tenancy\Identification\Drivers\Http\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;

class EagerLoadingTest extends TestCase
{
    /** @test */
    public function by_default_the_middleware_is_not_added()
    {
        $kernel = $this->app->make(Kernel::class);
        $this->assertFalse($kernel->hasMiddleware(EagerIdentification::class));
    }

    /** @test */
    public function the_middleware_gets_registered()
    {
        $this->app->register(IdentificationProvider::class);

        $kernel = $this->app->make(Kernel::class);
        $this->assertTrue($kernel->hasMiddleware(EagerIdentification::class));
    }

    /** @test */
    public function the_config_decides_if_the_middleware_is_registered()
    {
        config(['tenancy.identification-driver-http.eager' => false]);

        $this->app->register(IdentificationProvider::class);

        $kernel = $this->app->make(Kernel::class);
        $this->assertFalse($kernel->hasMiddleware(EagerIdentification::class));
    }
}
