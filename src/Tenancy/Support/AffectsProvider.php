<?php

namespace Tenancy\Support;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Tenancy\Identification\Events\Switched;

abstract class AffectsProvider extends ServiceProvider
{
    /**
     * Listeners that affect the app logic when a tenant
     * is resolved or switched to.
     *
     * @var array
     */
    protected $affects = [];

    public function register()
    {
        foreach ($this->affects as $affect) {
            Event::listen(Switched::class, $affect);
        }
    }
}