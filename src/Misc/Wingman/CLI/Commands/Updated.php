<?php

namespace Tenancy\Misc\Wingman\CLI\Commands;

use Tenancy\Tenant\Events\Updated as Event;

class Updated extends EventBaseCommand
{
    /** @var string */
    protected $event = Event::class;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wingman:updated
                            {--chunk=100 : Chunks the tenants with the specific amount}
                            {--tenant-identifiers= : Defines the tenant identifier that should be used}
                            {--tenants= : Defines the tenants that should be used}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Triggers the updated of tenants on all tenant models';
}
