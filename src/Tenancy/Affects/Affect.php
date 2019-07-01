<?php

namespace Tenancy\Affects;

use Tenancy\Contracts\TenantAffectsApp;
use Tenancy\Identification\Events\Switched;
use Tenancy\Pipeline\Step;

abstract class Affect extends Step implements TenantAffectsApp
{
    public function fires(): bool
    {
        return $this->event instanceof Switched;
    }
}
