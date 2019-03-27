<?php

namespace Tenancy\Contracts;

use Tenancy\Identification\Events\Resolved;
use Tenancy\Identification\Events\Switched;

interface TenantAffectsApp
{
    /**
     * @param Resolved|Switched $event
     * @return void|bool
     */
    public function handle($event);
}