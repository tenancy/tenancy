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
use Tenancy\Affects\Views\Events\ConfigureViews;
use Tenancy\Affects\Views\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsIntegrationTest;
use Tenancy\Tests\Affects\Views\AddsPaths;

class ConfigureViewsPathTest extends AffectsIntegrationTest
{
    use AddsPaths;

    protected $additionalProviders = [Provider::class];

    /** @test */
    public function it_can_render_the_loaded_views()
    {
        Tenancy::setTenant($this->tenant);

        $factory = $this->app->make(Factory::class);

        $this->assertStringContainsString(
            "Testing",
            $factory->make('test')->render()
        );
    }
}
