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

namespace Tenancy\Identification\Drivers\Queue\Events;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;
use Illuminate\Support\Arr;
use Tenancy\Identification\Contracts\Tenant;

class Processing
{
    use SerializesAndRestoresModelIdentifiers;

    /**
     * @var JobProcessing
     */
    public $event;

    /**
     * @var Tenant|null
     */
    public $tenant;

    /**
     * @var string|null
     */
    public $tenant_key;

    /**
     * @var string|null
     */
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
            $job = $this->unserializeToJob($command);
        }

        $tenant = null;
        if ($job->tenant) {
            $tenant = $this->restoreModel($job->tenant);
        }

        $this->tenant = $tenant ?? null;
        $this->tenant_key = $job->tenant_key ?? $payload['tenant_key'] ?? null;
        $this->tenant_identifier = $job->tenant_identifier ?? $payload['tenant_identifier'] ?? null;

        $this->job = $command;
    }

    /**
     * Unserializes the job to a simple job.
     *
     * @param string $object
     *
     * @return object
     */
    private function unserializeToJob(string $object)
    {
        $stdClassObj = preg_replace('/^O:\d+:"[^"]++"/', 'O:'.strlen('stdClass').':"stdClass"', $object);

        return unserialize($stdClassObj);
    }
}
