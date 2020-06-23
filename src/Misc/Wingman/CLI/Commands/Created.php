<?php

namespace Tenancy\Misc\Wingman\CLI\Commands;

use Tenancy\Tenant\Events\Created as Event;

class Created extends EventBaseCommand
{
    /** @var string */
    protected $event = Event::class;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wingman:created
                            {--chunk=100 : Chunks the tenants with the specific amount}
                            {--tenant-identifiers= : Defines the tenant identifier that should be used}
                            {--tenants= : Defines the tenants that should be used}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Triggers the creation of tenants on all tenant models';
}
