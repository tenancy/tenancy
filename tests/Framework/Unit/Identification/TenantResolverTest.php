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
