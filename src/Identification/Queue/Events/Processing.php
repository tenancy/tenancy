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

    protected $command;

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

        $this->command = $command;
    }

    public function resolve(): ?Tenant
    {
        app()->instance(self::class, $this);

        $resolver = $this->resolver();
        return $resolver();
    }

    public function tenant(): ?Tenant
    {
        if ($this->tenant_identifier && $this->tenant_key) {
            return $this->resolver()->findModel($this->tenant_identifier, $this->tenant_key);
        }

        return null;
    }

    public function switch(Tenant $tenant = null)
    {
        /** @var Environment $environment */
        $environment = app(Environment::class);

        $environment->setTenant($tenant ?? $this->tenant());

        return $this;
    }

    protected function resolver(): ResolvesTenants
    {
        return app(ResolvesTenants::class);
    }
}
