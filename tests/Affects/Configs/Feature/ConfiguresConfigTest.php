<?php

namespace Tenancy\Tests\Affects\Configs\Feature;

use Tenancy\Affects\Configs\Events\ConfigureConfig;
use Tenancy\Affects\Configs\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsTestCase;

class ConfiguresConfigTest extends AffectsTestCase
{
    protected $additionalProviders = [Provider::class];

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureConfig::class, function (ConfigureConfig $event) {
            $event->config->set('tenant', $event->event->tenant->getTenantKey());
        });
    }

    protected function isAffected(Tenant $tenant)
    {
        return config('tenant') === $tenant->getTenantKey();
    }
}
