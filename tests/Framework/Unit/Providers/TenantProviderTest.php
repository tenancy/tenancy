<?php

namespace Tenancy\Tests\Framework\Unit\Providers;

use Tenancy\Providers\TenantProvider;
use Tenancy\Testing\TestCase;

class TenantProviderTest extends TestCase
{
    /** @var TenantProvider */
    private $provider;

    protected function afterSetUp()
    {
        $this->provider = new TenantProvider($this->app);
    }

    /** @test */
    public function all_provides_are_registered()
    {
        foreach($this->provider->provides() as $abstract){
            $this->assertTrue($this->app->bound($abstract));
        }
    }
}
