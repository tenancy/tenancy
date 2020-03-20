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

namespace Tenancy\Tests\Framework\Unit;

use Tenancy\Environment;
use Tenancy\Testing\TestCase;

class EnvironmentTest extends TestCase
{
    /** @test */
    public function it_is_registered_as_singleton()
    {
        $tenant = $this->mockTenant();

        $environment = $this->app->make(Environment::class);
        $environment->setTenant($tenant);

        $newEnvironment = resolve(Environment::class);

        $this->assertEquals(
            $tenant,
            $newEnvironment->getTenant()
        );

        $this->assertEquals(
            $environment,
            $newEnvironment
        );
    }
}
