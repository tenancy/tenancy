<?php

namespace Tenancy\Tests\Affects\Routes\Integration;

use Tenancy\Affects\Routes\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsIntegrationTest;
use Tenancy\Tests\Affects\Routes\AddsFromFile;

class ConfigureRoutesHelperTest extends AffectsIntegrationTest
{
    use AddsFromFile;

    protected $additionalProviders = [Provider::class];

    /** @test */
    public function registered_routes_are_loaded()
    {
        Tenancy::setTenant($this->tenant);

        $this->assertEquals(
            "http://localhost/test",
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
            "http://localhost/nested/test",
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
