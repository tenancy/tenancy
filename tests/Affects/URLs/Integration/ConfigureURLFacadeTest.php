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

namespace Tenancy\Tests\Affects\URLs\Integration;

use Illuminate\Support\Facades\URL;
use Tenancy\Affects\URLs\Events\ConfigureURL;
use Tenancy\Affects\URLs\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsIntegrationTestCase;

class ConfigureURLFacadeTest extends AffectsIntegrationTestCase
{
    protected $additionalProviders = [Provider::class];

    /** @test */
    public function by_default_the_url_facade_is_not_affected()
    {
        $this->assertNotEquals(
            $this->tenant->getTenantKey().'.tenancy.dev',
            URL::current()
        );
    }

    /** @test */
    public function changing_the_url_will_change_the_url_facade_base()
    {
        Tenancy::setTenant($this->tenant);

        $this->assertEquals(
            $this->tenant->getTenantKey().'.tenancy.dev',
            URL::current()
        );
    }

    /** @test */
    public function changing_the_url_will_change_the_url_facade_to()
    {
        Tenancy::setTenant($this->tenant);

        $this->assertEquals(
            $this->tenant->getTenantKey().'.tenancy.dev/testing',
            URL::to('testing')
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
            $event->changeRoot($event->event->tenant->getTenantKey().'.tenancy.dev');
        });
    }
}
