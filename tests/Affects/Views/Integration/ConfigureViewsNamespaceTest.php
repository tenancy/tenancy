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

namespace Tenancy\Tests\Affects\Views\Integration;

use Illuminate\Contracts\View\Factory;
use Tenancy\Affects\Views\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsIntegrationTestCase;
use Tenancy\Tests\Affects\Views\AddsNamespaces;

class ConfigureViewsNamespaceTest extends AffectsIntegrationTestCase
{
    use AddsNamespaces;

    protected $additionalProviders = [Provider::class];

    /** @test */
    public function registered_views_can_be_rendered()
    {
        Tenancy::setTenant($this->tenant);

        /** @var Factory */
        $factory = $this->app->make(Factory::class);

        $this->assertStringContainsString(
            'Testing',
            $factory->make('tenant::test')->render()
        );
    }
}
