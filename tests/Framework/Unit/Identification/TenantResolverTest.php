<?php

namespace Tenancy\Tests\Framework\Unit\Identification;

use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Models\SimpleModel;

class TenantResolverTest extends TestCase
{
    /** @var ResolvesTenants */
    private $resolver;

    protected function afterSetUp()
    {
        $this->resolver = $this->app->make(ResolvesTenants::class);
    }

    /** @test */
    public function it_validates_the_add_model_input()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->resolver->addModel(SimpleModel::class);
    }
}
