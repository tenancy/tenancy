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

namespace Tenancy\Identification\Drivers\Queue\Events;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Arr;
use Tenancy\Identification\Contracts\Tenant;

class Processing
{
    /**
     * @var JobProcessing
     */
    public $event;
    /** @var Tenant|null */
    public $tenant;
    /** @var string|null */
    public $tenant_key;
    /** @var string|null */
    public $tenant_identifier;
    /**
     * @var Job
     */
    public $job;

    public function __construct(JobProcessing $event)
    {
        $this->event = $event;

        /** @var array $payload */
        $payload = $event->job->payload();

        if ($command = Arr::get($payload, 'data.command')) {
            $command = unserialize($command);
        }

        $this->tenant = $command->tenant ?? null;
        $this->tenant_key = $command->tenant_key ?? $payload['tenant_key'] ?? null;
        $this->tenant_identifier = $command->tenant_identifier ?? $payload['tenant_identifier'] ?? null;

        $this->job = $command;
    }
}
