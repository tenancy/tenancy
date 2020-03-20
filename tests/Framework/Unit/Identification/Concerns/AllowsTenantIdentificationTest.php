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

namespace Tenancy\Tests\Framework\Unit\Identification\Concerns;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Tenancy\Identification\Concerns\AllowsTenantIdentification;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\TestCase;

class AllowsTenantIdentificationTest extends TestCase
{
    protected $class;

    protected function afterSetUp()
    {
        $this->class = new class() extends Model {
            use AllowsTenantIdentification;
        };
    }

    /** @test */
    public function has_required_methods()
    {
        $has = collect((new ReflectionClass($this->class))->getMethods())->pluck('name');
        $needs = collect((new ReflectionClass(Tenant::class))->getMethods())->pluck('name');

        $this->assertCount(
            $needs->count(),
            $has->intersect($needs),
            AllowsTenantIdentification::class.' does not implement all required interface methods from '.Tenant::class
        );
    }
}
