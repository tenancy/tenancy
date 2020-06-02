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

namespace Tenancy\Hooks\Database\Events\Drivers;

use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Identification\Contracts\Tenant;

abstract class ResultEvent
{
    /**
     * @var Tenant
     */
    public $tenant;

    /**
     * @var ProvidesDatabase
     */
    public $provider;

    /**
     * @var bool
     */
    public $result;

    public function __construct(Tenant $tenant, ProvidesDatabase $provider, bool $result)
    {
        $this->tenant = $tenant;
        $this->provider = $provider;
        $this->result = $result;
    }
}
