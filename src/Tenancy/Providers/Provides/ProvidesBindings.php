<?php

namespace Tenancy\Providers\Provides;

trait ProvidesBindings
{
    protected function registerProvidesBindings()
    {
        foreach ($this->singletons as $singleton) {
            $this->app->singleton($singleton);
        }
    }
}
