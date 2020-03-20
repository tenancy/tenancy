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
use Tenancy\Tests\Affects\Views\ReplacesPaths;

class ConfigureViewsReplacesPathTest extends AffectsIntegrationTestCase
{
    use ReplacesPaths;

    protected $additionalProviders = [Provider::class];

    /** @test */
    public function replaced_views_can_be_rendered()
    {
        /** @var Factory */
        $factory = $this->app->make(Factory::class);

        $this->assertStringNotContainsString(
            'Welcome',
            $factory->make('welcome')->render(),
            "Laravel's default template contains welcome already"
        );

        Tenancy::setTenant($this->tenant);

        $this->assertStringContainsString(
            'Welcome',
            $factory->make('welcome')->render()
        );
    }
}
