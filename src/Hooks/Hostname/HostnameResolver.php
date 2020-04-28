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

namespace Tenancy\Hooks\Hostname;

use Tenancy\Hooks\Hostname\Contracts\ProvidesHostname;
use Tenancy\Hooks\Hostname\Contracts\ResolvesHostnames;
use Tenancy\Identification\Contracts\Tenant;

class HostnameResolver implements ResolvesHostnames
{
    public function __invoke(Tenant $tenant): ?ProvidesHostname
    {
        /** @var ProvidesHostnames|null $provider */
        $provider = $this->events->until(new Events\Resolving($tenant));

        if ($provider) {
            $this->events->dispatch(new Events\Identified($tenant, $provider));
        }

        $this->events->dispatch(new Events\Resolved($tenant, $provider));

        return $provider;
    }
}
