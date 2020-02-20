<?php

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
