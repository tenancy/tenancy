<?php

namespace Tenancy\Tests\Framework\Unit\Identification;

use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Support\TenantModelCollection;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

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
        $this->assertNull($this->resolver->findModel(-100));
    }

    /** @test */
    public function find_model_can_find_models()
    {
        $this->resolver->addModel(Tenant::class);

        $tenant = $this->createMockTenant();

        $this->assertNotNull(
            $this->resolver->findModel($tenant->getTenantIdentifier())
        );
    }
}
