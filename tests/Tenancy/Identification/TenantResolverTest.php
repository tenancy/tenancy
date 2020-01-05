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

namespace Tenancy\Tests\Framework\Identification;

use Illuminate\Foundation\Auth\User;
use InvalidArgumentException;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;
use Tenancy\Identification\Events\Resolving;
use Tenancy\Identification\Support\TenantModelCollection;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Framework\Identification\Mocks\IdentifiesByTest;
use Tenancy\Tests\Framework\Identification\Mocks\TenantIdentifiableByMany;
use Tenancy\Tests\Framework\Identification\Mocks\TenantIdentifiableByTest;

class TenantResolverTest extends TestCase
{
    /** @var ResolvesTenants */
    protected $resolver;

    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->resolver = $this->app->make(ResolvesTenants::class);
        $this->tenant = $this->mockTenant();
    }

    /**
     * @test
     */
    public function can_resolve_a_tenant()
    {
        $this->resolveTenant($this->tenant);

        /** @var Tenant $tenant */
        $tenant = $this->resolver->__invoke();

        $this->assertEquals($this->tenant->getTenantIdentifier(), $tenant->getTenantIdentifier());
    }

    /**
     * @test
     */
    public function fails_registering_invalid_model()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->resolver->addModel(User::class);
    }

    /**
     * @test
     */
    public function registers_valid_model()
    {
        $this->resolver->addModel(Tenant::class);

        $this->assertCount(1, $this->resolver->getModels());
    }

    /**
     * @test
     */
    public function allows_providers_to_match_models()
    {
        $this->events->listen(Resolving::class, function (Resolving $event) {
            $event->models->each(function (string $class) {
                $this->assertEquals(Tenant::class, $class);
            });
        });

        $this->resolver->addModel(Tenant::class);

        $this->resolver->__invoke();
    }

    /**
     * @test
     */
    public function register_valid_models()
    {
        $this->resolver->setModels(new TenantModelCollection([Tenant::class]));

        $this->assertCount(1, $this->resolver->getModels());
        $this->assertEquals(Tenant::class, $this->resolver->getModels()->first());
    }

    /**
     * @test
     */
    public function can_identify_by_contract()
    {
        $this->resolver->registerDriver(IdentifiesByTest::class);
        $this->resolver->addModel(TenantIdentifiableByTest::class);

        $tenant = $this->createMockTenant();

        $identified = $this->resolver->__invoke(IdentifiesByTest::class);

        $this->assertInstanceOf(
            TenantIdentifiableByTest::class,
            $identified
        );

        $this->assertEquals(
            $tenant->getTenantKey(),
            $identified->getTenantKey()
        );
    }

    /**
     * @test
     */
    public function identify_by_contract_does_not_trigger_others()
    {
        $this->resolver->registerDriver(IdentifiesByTest::class);
        $this->resolver->registerDriver(IdentifiesByEnvironment::class);
        $this->resolver->addModel(TenantIdentifiableByMany::class);

        $tenant = $this->createMockTenant();

        $identified = $this->resolver->__invoke(IdentifiesByTest::class);

        $this->assertInstanceOf(
            TenantIdentifiableByMany::class,
            $identified
        );

        $this->assertEquals(
            $tenant->getTenantKey(),
            $identified->getTenantKey()
        );

        $this->expectException(
            InvalidArgumentException::class
        );

        $this->resolver->__invoke(IdentifiesByEnvironment::class);
    }
}
