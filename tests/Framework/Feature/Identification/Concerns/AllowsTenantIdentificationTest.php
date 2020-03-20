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

namespace Tenancy\Tests\Framework\Feature\Identification\Concerns;

use Illuminate\Database\Eloquent\Model;
use Tenancy\Identification\Concerns\AllowsTenantIdentification;
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
    public function tenant_key_name_returns_the_model_key_name()
    {
        $this->assertEquals(
            $this->class->getKeyName(),
            $this->class->getTenantKeyName()
        );
    }

    /** @test */
    public function tenant_key_returns_the_model_key()
    {
        $this->assertEquals(
            $this->class->getKey(),
            $this->class->getTenantKey()
        );
    }

    /** @test */
    public function tenant_identifier_contains_the_table()
    {
        $this->assertStringContainsString(
            $this->class->getTable(),
            $this->class->getTenantIdentifier()
        );
    }

    /** @test */
    public function by_default_the_tenant_identifier_uses_the_database_config()
    {
        $this->assertStringContainsString(
            config('database.default'),
            $this->class->getTenantIdentifier()
        );
    }
}
