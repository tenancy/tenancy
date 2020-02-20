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

namespace Tenancy\Testing;

use Illuminate\Foundation\Testing;

abstract class TestCase extends Testing\TestCase
{
    use Concerns\CreatesApplication;
    use Concerns\InteractsWithTenants;
    use Testing\RefreshDatabase;

    protected function beforeBoot()
    {
        // ..
    }

    protected function afterSetUp()
    {
        // ..
    }

    protected function beforeTearDown()
    {
        // ..
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->beforeBoot();
        $this->bootTenancy();
        $this->afterSetUp();
    }

    protected function tearDown(): void
    {
        $this->beforeTearDown();
        $this->tearDownTenancy();

        parent::tearDown();
    }
}
