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

namespace Tenancy\Tests\Affects\URLs\Feature;

use Illuminate\Support\Facades\URL;
use Tenancy\Affects\URLs\Events\ConfigureURL;
use Tenancy\Affects\URLs\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Affects\AffectShouldBeUndone;

class ConfigureURLTest extends AffectsFeatureTestCase
{
    use AffectShouldBeUndone;

    protected $additionalProviders = [Provider::class];

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureURL::class, function (ConfigureURL $event) {
            if ($event->event->tenant) {
                $event->changeRoot($event->event->tenant->name.'.tenant');
            }
        });
    }

    protected function isAffected(Tenant $tenant): bool
    {
        return $tenant->name.'.tenant' === URL::current();
    }
}
