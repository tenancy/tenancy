<?php

namespace Tenancy\Concerns;

use Tenancy\Identification\Contracts\ResolvesTenants as Resolver;

trait ResolvesTenants
{
    protected function resolver(): Resolver
    {
        return resolve(Resolver::class);
    }
}