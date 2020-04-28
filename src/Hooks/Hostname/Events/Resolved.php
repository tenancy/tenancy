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

namespace Tenancy\Hooks\Hostname\Events;

use Tenancy\Hooks\Hostname\Contracts\ProvidesHostname;
use Tenancy\Identification\Contracts\Tenant;

class Resolved
{
    /**
     * @var Tenant|null
     */
    public $tenant;

    /**
     * @var ProvidesHostname|null
     */
    public $provider;

    public function __construct(Tenant $tenant = null, ProvidesHostname &$provider = null)
    {
        $this->tenant = $tenant;
        $this->provider = &$provider;
    }
}
