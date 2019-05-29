<?php declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Unit\Providers;

use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;
use Tenancy\Identification\Contracts\Tenant;

class TenantProviderTest extends TestCase
{
    /**
     * @test
     */
    public function provider_resolves_null()
    {
        $this->assertNull(resolve(Tenant::class));
    }

    /**
     * @test
     */
    public function provider_resolves_tenant()
    {
        $tenant = $this->mockTenant();

        $this->resolveTenant($tenant);

        $this->assertEquals(
            $tenant,
            resolve(Tenant::class)
        );
    }
}
