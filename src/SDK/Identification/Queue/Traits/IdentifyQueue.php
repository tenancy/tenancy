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

    /**
     * Identifies the tenant based on the id key
     *
     * @param Processing $event
     *
     * @return Tenant|null
     */
    protected function queueIdentifyKey(Processing $event) {
        return $this->newQuery()->where($this->getTenantKeyName(), $event->tenant_key)->first();
    }

    /**
     * Identifies the tenant based on the combination of keys
     *
     * @param Processing $event
     *
     * @return Tenant|null
     */
    protected function queueIdentifyCombination(Processing $event) {
        if($this->getTenantIdentifier() != $event->tenant_identifier) {
            return null;
        }

        return $this->queueIdentifyKey($event);
    }

    /**
     * Identifies the tenant based on the provided tenant
     *
     * @param Processing $event
     *
     * @return Tenant|null
     */
    protected function queueIdentifyModel(Processing $event) {
        return $event->tenant;
    }

    /**
     * Identifies the tenant based on the provided tenant or a combination.
     *
     * @param Processing $event
     *
     * @return Tenant|null
     */
    protected function queueIdentifyPreferModel(Processing $event) {
        $possibleTenant = $this->queueIdentifyModel($event);

        if ($possibleTenant) {
            return $possibleTenant;
        }

        return $this->queueIdentifyCombination($event);
    }
}
