<?php

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

use Illuminate\Database\Eloquent\Model;
use Tenancy\Eloquent\ConnectionResolver;

trait ProvidesEloquentConnections
{
    protected function registerProvidesEloquentConnections()
    {
        $this->app->extend('db', function ($manager) {
            $resolver = $this->app->makeWith(ConnectionResolver::class, compact('manager'));

            Model::setConnectionResolver($resolver);

            return $resolver;
        });
    }
}
