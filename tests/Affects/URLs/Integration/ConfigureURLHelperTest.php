<?php

namespace Tenancy\Tests\Affects\URLs\Integration;

use Tenancy\Affects\URLs\Events\ConfigureURL;
use Tenancy\Affects\URLs\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsIntegrationTest;

class ConfiguresURLHelperTest extends AffectsIntegrationTest
{
    protected $additionalProviders = [Provider::class];

    /** @test */
    public function by_default_the_url_helper_is_not_affected()
    {
        $this->assertNotEquals(
            $this->tenant->getTenantKey() . '.tenancy.dev',
            url('')
        );
    }

    /** @test */
    public function changing_the_url_will_change_the_url_helper_base()
    {
        Tenancy::setTenant($this->tenant);

        $this->assertEquals(
            $this->tenant->getTenantKey() . '.tenancy.dev',
            url('')
        );
    }

    /** @test */
    public function changing_the_url_will_change_the_url_helper_with_path()
    {
        Tenancy::setTenant($this->tenant);

        $this->assertEquals(
            $this->tenant->getTenantKey() . '.tenancy.dev/testing',
            url('testing')
        );
    }

    /**
     * Registers the affect functionality in the applicatio.
     *
     * @return void
     */
    protected function registerAffecting()
    {
        $this->events->listen(ConfigureURL::class, function (ConfigureURL $event) {
            $event->changeRoot($event->event->tenant->getTenantKey() . '.tenancy.dev');
        });
    }
}