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

namespace Tenancy\Tests\Identification\Http\Integration;

use Tenancy\Environment;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Drivers\Http\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;

class RequestTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];

    /** @test */
    public function it_triggers_identification_on_incoming_requests()
    {
        $this->mock(Environment::class, function ($mock) {
            $mock
                ->shouldReceive('isIdentified')
                ->andReturn(false);
            $mock
                ->shouldReceive('identifyTenant')
                ->once()
                ->withArgs([
                    false,
                    IdentifiesByHttp::class,
                ]);
        });

        $this->get('/');
    }

    /** @test */
    public function it_does_not_trigger_identification_when_a_tenant_is_already_identified()
    {
        $this->mock(Environment::class, function ($mock) {
            $mock
                ->shouldReceive('isIdentified')
                ->andReturn(true);
            $mock
                ->shouldNotReceive('identifyTenant');
        });

        $this->get('/');
    }
}
