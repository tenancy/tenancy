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

namespace Tenancy\Tests\Affects;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\TestCase;

abstract class AffectsIntegrationTestCase extends TestCase
{
    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
        $this->registerAffecting();
    }

    /**
     * Registers the affect functionality in the applicatio.
     *
     * @return void
     */
    abstract protected function registerAffecting();
}
