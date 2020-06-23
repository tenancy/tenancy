<?php

namespace Tenancy\Misc\Wingman;

use Tenancy\Support\Provider as SupportProvider;
use Tenancy\Misc\Wingman\CLI\Commands;

class Provider extends SupportProvider
{
    public function register()
    {
        parent::register();

        $this->commands([
            Commands\Created::class,
            Commands\Updated::class,
            Commands\Deleted::class,
            Commands\ListCommand::class,
        ]);
    }
}
