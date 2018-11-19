<?php

namespace Tenancy\Providers\Provides;

use Illuminate\Database\DatabaseManager;
use Tenancy\Environment;

trait ProvidesEloquent
{
    protected function bootProvidesEloquent()
    {
        $this->app->resolving('db', function (DatabaseManager $manager) {
            if (config('tenancy.models-default-to-tenant-connection')) {
                $manager->setDefaultConnection(config('tenancy.database.tenant-connection-name'));
            }

            if (config('tenancy.models-default-to-system-connection')) {
                $manager->setDefaultConnection(Environment::getDefaultSystemConnectionName());
            }

            return $manager;
        });
    }
}
