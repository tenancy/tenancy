<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Database;

use Tenancy\Testing\TestCase;
use Tenancy\Database\Events\Resolving;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Updated;
use Tenancy\Tenant\Events\Deleted;

class AutoListenerTest extends TestCase
{
    protected function register_db_driver()
    {
        $this->events->forget(Resolving::class);

        $this->events->listen(Resolving::class, function (Resolving $event) {
            return new Mocks\NullDriver;
        });
    }

    /**
     * @test
     */
    public function created_valid_responses()
    {
        $tenant = $this->mockTenant();

        $this->assertNull($this->events->dispatch(new Created($tenant))[0]);

        $this->register_db_driver();

        $this->assertTrue($this->events->dispatch(new Created($tenant))[0]);
    }

    /**
     * @test
     */
    public function updated_valid_responses()
    {
        $tenant = $this->mockTenant();

        $this->assertNull($this->events->dispatch(new Updated($tenant))[0]);

        $this->register_db_driver();

        $this->assertTrue($this->events->dispatch(new Updated($tenant))[0]);
    }

    /**
     * @test
     */
    public function deleted_valid_responses()
    {
        $tenant = $this->mockTenant();

        $this->assertNull($this->events->dispatch(new Deleted($tenant))[0]);

        $this->register_db_driver();

        $this->assertTrue($this->events->dispatch(new Deleted($tenant))[0]);
    }
}
