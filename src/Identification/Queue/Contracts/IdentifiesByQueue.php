<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Identification\Drivers\Queue\Contracts;

use Illuminate\Queue\Events\JobProcessing;
use Tenancy\Identification\Contracts\Tenant;

interface IdentifiesByQueue
{
    /**
     * Specify whether the tenant model is matching the job.
     *
     * @param JobProcessing $event
     *
     * @return Tenant
     */
    public function tenantIdentificationByQueue(JobProcessing $event): ?Tenant;
}
