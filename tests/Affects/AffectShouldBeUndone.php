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

trait AffectShouldBeUndone
{
    /** @test */
    public function affects_can_be_undone()
    {
        $this->registerAffecting();
        $this->identifyTenant($this->tenant);

        $this->assertAffected($this->tenant);

        $this->identifyTenant(null);

        $this->assertNotAffected($this->tenant);
    }
}
