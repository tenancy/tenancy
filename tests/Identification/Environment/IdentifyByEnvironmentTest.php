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

namespace Tenancy\Tests\Identification\Http;

use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\Adapter\ServerConstAdapter;
use Dotenv\Environment\DotenvFactory;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Environment\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Identification\Environment\Mocks\TenantIdentifiableByEnvironment;

class IdentifyByEnvironmentTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];
    protected $additionalMocks = [__DIR__.'/Mocks/factories/'];

    /** @var User */
    protected $user;

    /** @var TenantIdentifiableByEnvironment */
    protected $tenant;

    protected function afterSetUp()
    {
        /** @var ResolvesTenants $resolver */
        $resolver = resolve(ResolvesTenants::class);
        $resolver->addModel(TenantIdentifiableByEnvironment::class);

        $this->tenant = $this->createMockTenant();
    }

    /**
     * @test
     */
    public function request_identifies_tenant()
    {
        $this->assertFalse($this->environment->isIdentified());

        $this->setEnv('TENANT_NAME', $this->tenant->name);

        $this->assertEquals($this->tenant->name, optional($this->environment->identifyTenant())->name);

        $this->assertTrue($this->environment->isIdentified());
    }

    protected function setEnv($name, $value = null)
    {
        $env = (new DotenvFactory([new EnvConstAdapter(), new ServerConstAdapter()]))->create();
        $env->set($name, $value);

        $this->assertEquals($value, env($name));
    }
}
