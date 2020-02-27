<?php

namespace Tenancy\Tests\Framework\Feature\Support;

use Tenancy\Support\Contracts\ProvidesPassword;
use Tenancy\Support\PasswordGenerator;
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
