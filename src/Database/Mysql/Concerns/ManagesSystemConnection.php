<?php

namespace Tenancy\Database\Drivers\Mysql\Concerns;

trait ManagesSystemConnection
{
    /**
     * Allows overriding the system connection used for the tenant.
     *
     * @return null|string
     */
    abstract public function getManagingSystemConnection(): ?string;
}
