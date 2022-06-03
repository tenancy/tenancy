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

namespace Tenancy\Tests\Affects\Configs\Integration;

use Tenancy\Affects\Configs\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsIntegrationTestCase;
use Tenancy\Tests\Affects\Configs\ThroughConfig;

class ThroughConfigTest extends AffectsIntegrationTestCase
{
    use ThroughConfig;

    protected array $additionalProviders = [Provider::class];

    /** @test */
    public function by_default_the_helper_result_has_no_value()
    {
        $this->assertNull(config('testing.tenant'));
    }

    /** @test */
    public function it_changes_the_result_of_the_helper()
    {
        Tenancy::setTenant($this->tenant);

        $this->assertEquals(
            $this->tenant->getTenantKey(),
            config('testing.tenant')
        );
    }
}
