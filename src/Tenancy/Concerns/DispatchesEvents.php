<?php

namespace Tenancy\Concerns;

use Illuminate\Contracts\Events\Dispatcher;

trait DispatchesEvents
{
    protected function events(): Dispatcher
    {
        return resolve(Dispatcher::class);
    }
}