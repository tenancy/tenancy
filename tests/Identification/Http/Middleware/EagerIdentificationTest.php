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

namespace Tenancy\Tests\Identification\Http\Middleware;

use Illuminate\Http\Request;
use Tenancy\Identification\Drivers\Http\Middleware\EagerIdentification;
use Tenancy\Testing\TestCase;

class EagerIdentificationTest extends TestCase
{
    /**
     * @test
     */
    public function is_eagerly_identifying_tenant()
    {
        $this->assertFalse($this->environment->isIdentified());

        $middleware = new EagerIdentification($this->app);

        $middleware->handle(new Request(), function () {
        });

        $this->assertTrue($this->environment->isIdentified());
    }
}
