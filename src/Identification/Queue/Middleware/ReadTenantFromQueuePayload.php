<?php

namespace Tenancy\Identification\Drivers\Queue\Middleware;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Queue\Events\JobProcessing;
use Tenancy\Identification\Drivers\Queue\Events\Processing;

class ReadTenantFromQueuePayload
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function __invoke(JobProcessing $event)
    {
        $processing = new Processing($event);

        // Bind this event into ioc for use in the tenant model resolving.
        $this->app->instance(Processing::class, $processing);

        event($processing);
    }
}
