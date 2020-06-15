<?php

namespace Tenancy\SDK\Identification\Queue\Traits;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Queue\Events\Processing;

trait IdentifyQueue
{
    /**
     * Identifies the tenant through the mode specified in the config.
     *
     * @param Processing $event
     *
     * @return Tenant|null
     */
    public function tenantIdentificationByQueue(Processing $event): ?Tenant{
        $function = 'queueIdentify' . config('tenancy.sdk.identification.queue.mode');

        return $this->{$function}($event);
    }

    /**
     * Always identifies as null
     *
     * @param Processing $event
     *
     * @return null
     */
    protected function queueIdentifyNone(Processing $event) {
        return null;
    }

    /**
     * Always identifies as the first of the query.
     *
     * @param Processing $event
     *
     * @return null
     */
    protected function queueIdentifyFirst(Processing $event) {
        return $this->newQuery()->first();
    }

    /**
     * Dump dies the event.
     *
     * @param Processing $event
     *
     * @return void
     */
    protected function queueIdentifyDie(Processing $event) {
        dd($event);
    }

    /**
     * Dumps the event and returns null
     *
     * @param Processing $event
     *
     * @return null
     */
    protected function queueIdentifyDump(Processing $event) {
        dump($event);

        return null;
    }
}
