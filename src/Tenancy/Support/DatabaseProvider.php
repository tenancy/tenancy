<?php

namespace Tenancy\Support;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Tenancy\Database\Events\Resolving;

abstract class DatabaseProvider extends ServiceProvider
{
    use Concerns\PublishesConfigs;

    /**
     * Listener for the resolving event.
     *
     * @var string
     */
    protected $listener;

    public function register()
    {
        if ($this->listener) {
            Event::listen(Resolving::class, $this->listener);
        }

        $this->publishConfigs();
    }
}