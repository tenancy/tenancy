<?php

namespace Tenancy\Affects\Filesystem\Events;

use Tenancy\Identification\Events\Resolved;

class ConfigureDisk
{
    /**
     * @var Resolved
     */
    public $resolved;
    /**
     * @var array
     */
    public $config;

    public function __construct(Resolved $resolved, array &$config = [])
    {
        $this->resolved = $resolved;
        $this->config = $config;
    }
}
