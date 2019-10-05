<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Affects\URLs;

use Illuminate\Support\Facades\URL;
use Tenancy\Affects\URLs\Events\ConfigureURL;
use Tenancy\Affects\URLs\Provider;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Tests\Affects\AffectsTestCase;

class ConfiguresURLTest extends AffectsTestCase
{
    protected $additionalProviders = [Provider::class];

    protected function registerForwardingCall()
    {
        $this->events->listen(ConfigureURL::class, function (ConfigureURL $event) {
            $event->forceRootUrl($event->event->tenant->name. '.tenant');
        });
    }

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureURL::class, function (ConfigureURL $event) {
            $event->changeRoot($event->event->tenant->name. '.tenant');
        });
    }

    protected function assertNotAffected()
    {
        $this->assertStringNotContainsString(
            '.tenant',
            URL::current()
        );
    }

    protected function assertAffected(Tenant $tenant)
    {
        $this->assertEquals(
            $tenant->name . '.tenant',
            URL::current()
        );
    }
}
