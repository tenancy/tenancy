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

namespace Tenancy\Tests\Affects\Routes\Integration;

use Tenancy\Affects\Routes\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsIntegrationTestCase;
use Tenancy\Tests\Affects\Routes\AddsFromFile;

class ConfigureRoutesHelperTest extends AffectsIntegrationTestCase
{
    use AddsFromFile;

    protected $additionalProviders = [Provider::class];

    /** @test */
    public function registered_routes_are_loaded()
    {
        Tenancy::setTenant($this->tenant);

        $this->assertEquals(
            'http://localhost/test',
            route('test')
        );
    }

    /** @test */
    public function registered_routes_can_be_accessed()
    {
        Tenancy::setTenant($this->tenant);

        $this
            ->get(route('test'))
            ->assertOk();
    }

    /** @test */
    public function registered_routes_have_the_right_data()
    {
        Tenancy::setTenant($this->tenant);

        $this
            ->get(route('test'))
            ->assertSeeText('test');
    }

    /** @test */
    public function registered_nested_routes_are_loaded()
    {
        Tenancy::setTenant($this->tenant);

        $this->assertEquals(
            'http://localhost/nested/test',
            route('nested.test')
        );
    }

    /** @test */
    public function registered_nested_routes_can_be_accessed()
    {
        Tenancy::setTenant($this->tenant);

        $this
            ->get(route('nested.test'))
            ->assertOk();
    }

    /** @test */
    public function registered_nested_routes_have_the_right_data()
    {
        Tenancy::setTenant($this->tenant);

        $this
            ->get(route('nested.test'))
            ->assertSeeText('nested.test');
    }
}
