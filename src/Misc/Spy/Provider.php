<?php

namespace Tenancy\Misc\Spy;

use Tenancy\Support\Provider as SupportProvider;
use Tenancy\Misc\Spy\CLI\Commands;

class Provider extends SupportProvider
{
    public function register()
    {
        parent::register();

        $this->commands([
            Commands\Show::class,
        ]);
    }
}
