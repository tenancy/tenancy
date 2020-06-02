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

use Illuminate\Support\Facades\Event;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Support\TenantModelCollection;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Tenants\FirstMixedTenant;
use Tenancy\Tests\Mocks\Tenants\NullMixedTenant;

class TenantResolverTest extends TestCase
{
    /** @var ResolvesTenants */
    private $resolver;

    protected function afterSetUp()
    {
        $this->resolver = $this->app->make(ResolvesTenants::class);
    }

    /** @test */
    public function by_default_it_returns_an_empty_collection()
    {
        $this->assertInstanceOf(
            TenantModelCollection::class,
            $this->resolver->getModels()
        );

        $this->assertEmpty($this->resolver->getModels());
    }

    /** @test */
    public function it_can_add_models()
    {
        $this->resolver->addModel(Tenant::class);

        $this->assertNotEmpty(
            $this->resolver->getModels()
        );
    }

    /** @test */
    public function it_can_set_models()
    {
        $collection = new TenantModelCollection([Tenant::class]);
        $this->resolver->setModels(
            $collection
        );

        $this->assertEquals(
            $collection,
            $this->resolver->getModels()
        );
    }

    /** @test */
    public function find_model_returns_null()
    {
        $this->assertNull($this->resolver->findModel(NotATenant::class));
    }

    /** @test */
    public function find_model_can_find_the_right_model()
    {
        $this->resolver->addModel(Tenant::class);

        $tenant = $this->createMockTenant();

        $this->assertInstanceOf(
            Tenant::class,
            $this->resolver->findModel($tenant->getTenantIdentifier())
        );
    }

    /** @test */
    public function find_model_can_find_exact_model()
    {
        $this->resolver->addModel(Tenant::class);
        $this->resolver->addModel(NullMixedTenant::class);

        $this->createMockTenant();
        $tenant = $this->createMockTenant();
        $this->createMockTenant();

        $foundTenant = $this->resolver->findModel($tenant->getTenantIdentifier(), $tenant->getTenantKey());

        $this->assertInstanceOf(
            Tenant::class,
            $foundTenant
        );

        $this->assertEquals(
            $tenant->getTenantKey(),
            $foundTenant->getTenantKey()
        );
    }

    /** @test */
    public function identifying_will_use_all_drivers()
    {
        $this->resolver->addModel(NullMixedTenant::class);
        $this->resolver->registerDriver(IdentifiesByHttp::class);
        $this->resolver->registerDriver(IdentifiesByEnvironment::class);

        Event::fake([
            'mock.tenant.identification.http',
            'mock.tenant.identification.environment',
        ]);

        $this->resolver->__invoke();

        Event::assertDispatched('mock.tenant.identification.http');
        Event::assertDispatched('mock.tenant.identification.environment');
    }

    /** @test */
    public function identifying_a_not_registered_contract_will_not_cause_errors()
    {
        $this->resolver->registerDriver(IdentifiesByEnvironment::class);

        $this->resolver->__invoke(IdentifiesByHttp::class);

        // This will not be triggered if the above causes an error.
        $this->assertFalse(
            Tenancy::isIdentified()
        );
    }

    /** @test */
    public function it_can_identify_without_a_specific_driver()
    {
        $this->resolver->registerDriver(IdentifiesByEnvironment::class);
        $this->resolver->addModel(FirstMixedTenant::class);
        $this->createMockTenant();

        $this->assertNotNull($this->resolver->__invoke());
    }
}
