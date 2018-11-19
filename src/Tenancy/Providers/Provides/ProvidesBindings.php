<?php

namespace Tenancy\Providers\Provides;

trait ProvidesBindings
{
    protected function registerProvidesBindings()
    {
        foreach ($this->singletons as $contract => $singleton) {
            $this->app->singleton($contract, $singleton);
        }
    }
}
