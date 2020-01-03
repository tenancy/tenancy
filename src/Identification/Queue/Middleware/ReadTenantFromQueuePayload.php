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

namespace Tenancy\Identification\Drivers\Queue\Middleware;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Queue\Events\JobProcessing;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Queue\Events\Processing;

class ReadTenantFromQueuePayload
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var ResolvesTenants
     */
    private $resolver;

    public function __construct(Application $app, ResolvesTenants $resolver)
    {
        $this->app = $app;
        $this->resolver = $resolver;
    }

    public function __invoke(JobProcessing $event)
    {
        $processing = new Processing($event);

        $this->app->instance(Processing::class, $processing);

        Tenancy::getTenant(true);
    }
}
