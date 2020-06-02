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

namespace Tenancy\Tests\Framework\Feature\Identification;

use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Identification\Support\TenantModelCollection;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Tenants\NullConsoleTenant;
use Tenancy\Tests\Mocks\Tenants\NullEnvironmentTenant;
use Tenancy\Tests\Mocks\Tenants\NullHttpTenant;
use Tenancy\Tests\Mocks\Tenants\NullMixedTenant;
use Tenancy\Tests\Mocks\Tenants\NullQueueTenant;

class TenantModelCollectionTest extends TestCase
{
    /** @var TenantModelCollection */
    private $collection;

    protected function setUp(): void
    {
        $this->collection = new TenantModelCollection();
    }

    /** @test */
    public function it_can_filter_based_on_contract()
    {
        $this->collection->add(Tenant::class);

        $this->assertCount(0, $this->collection->filterByContract(IdentifiesByConsole::class));
        $this->assertCount(0, $this->collection->filterByContract(IdentifiesByEnvironment::class));
        $this->assertCount(0, $this->collection->filterByContract(IdentifiesByHttp::class));
        $this->assertCount(0, $this->collection->filterByContract(IdentifiesByQueue::class));

        $this->collection->add(NullConsoleTenant::class);
        $this->collection->add(NullEnvironmentTenant::class);
        $this->collection->add(NullHttpTenant::class);
        $this->collection->add(NullQueueTenant::class);

        $this->assertCount(1, $this->collection->filterByContract(IdentifiesByConsole::class));
        $this->assertCount(1, $this->collection->filterByContract(IdentifiesByEnvironment::class));
        $this->assertCount(1, $this->collection->filterByContract(IdentifiesByHttp::class));
        $this->assertCount(1, $this->collection->filterByContract(IdentifiesByQueue::class));

        $this->collection->add(NullMixedTenant::class);

        $this->assertCount(2, $this->collection->filterByContract(IdentifiesByConsole::class));
        $this->assertCount(2, $this->collection->filterByContract(IdentifiesByEnvironment::class));
        $this->assertCount(2, $this->collection->filterByContract(IdentifiesByHttp::class));
        $this->assertCount(2, $this->collection->filterByContract(IdentifiesByQueue::class));
    }
}
