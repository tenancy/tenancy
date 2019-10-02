<?php

namespace Tenancy\Identification\Drivers\Queue\Events;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Arr;
use Tenancy\Environment;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Contracts\Tenant;

class Processing
{
    /**
     * @var JobProcessing
     */
    public $event;
    public $tenant_key;
    public $tenant_identifier;
    public $job;

    public function __construct(JobProcessing $event)
    {
        $this->event = $event;

        /** @var array $payload */
        $payload = $event->job->payload();

        if ($command = Arr::get($payload, 'data.command')) {
            $command = unserialize($command);
        }

        $this->tenant_key = $command->tenant_key ?? $payload['tenant_key'] ?? null;
        $this->tenant_identifier = $command->tenant_identifier ?? $payload['tenant_identifier'] ?? null;

        $this->job = $command;
    }

    /**
     * Use the tenant resolver to identify a candidate tenant.
     *
     * @return Tenant|null
     */
    public function resolve(): ?Tenant
    {
        $resolver = $this->resolver();
        return $resolver();
    }

    /**
     * Retrieve a tenant based on properties in the job.
     *
     * @return Tenant|null
     */
    public function tenant(): ?Tenant
    {
        if ($this->tenant_identifier && $this->tenant_key) {
            return $this->resolver()->findModel($this->tenant_identifier, $this->tenant_key);
        }

        return null;
    }

    /**
     * Switch to a specified tenant or fall back on auto identification.
     *
     * @param Tenant|null $tenant
     * @return $this
     */
    public function switch(Tenant $tenant = null)
    {
        /** @var Environment $environment */
        $environment = resolve(Environment::class);

        $environment->setTenant($tenant ?? $this->tenant() ?? $this->resolve());

        return $this;
    }
    
    protected function resolver(): ResolvesTenants
    {
        return resolve(ResolvesTenants::class);
    }
}
