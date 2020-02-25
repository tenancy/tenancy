<?php

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
        foreach($this->provider->singletons as $abstract => $class){
            $this->assertInstanceOf(
                $class,
                $this->app->make($abstract)
            );
        }
    }

    /** @test */
    public function all_provides_are_registered()
    {
        foreach($this->provider->provides() as $abstract){
            $this->assertTrue($this->app->bound($abstract));
        }
    }
}
