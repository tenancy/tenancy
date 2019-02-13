<?php declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

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
