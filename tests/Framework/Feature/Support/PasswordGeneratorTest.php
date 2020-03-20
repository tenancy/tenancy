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

namespace Tenancy\Tests\Framework\Feature\Support;

use Tenancy\Support\Contracts\ProvidesPassword;
use Tenancy\Testing\TestCase;

class PasswordGeneratorTest extends TestCase
{
    /** @var ProvidesPassword */
    private $generator;

    protected function afterSetUp()
    {
        $this->generator = $this->app->make(ProvidesPassword::class);
    }

    /** @test */
    public function it_creates_the_same_password_every_time()
    {
        $tenant = $this->mockTenant();

        $password = $this->generator->__invoke($tenant);

        $this->assertEquals(
            $password,
            $this->generator->__invoke($tenant)
        );
    }

    /** @test */
    public function by_default_it_uses_the_app_key()
    {
        $tenant = $this->mockTenant();

        $password = $this->generator->__invoke($tenant);

        config(['app.key' => 'Changed']);

        $this->assertNotEquals(
            $password,
            $this->generator->__invoke($tenant)
        );
    }

    /** @test */
    public function it_uses_the_tenancy_key_rather_than_the_app_key()
    {
        $tenant = $this->mockTenant();

        $password = $this->generator->__invoke($tenant);

        config(['tenancy.key' => 'Changed']);

        $this->assertNotEquals(
            $password,
            $this->generator->__invoke($tenant)
        );
    }
}
