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

namespace Tenancy\Eloquent\Listeners;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OverridesModelConnections
{
    protected static $forced = [
        'tenant' => [],
        'system' => []
    ];

    public function subscribe(Dispatcher $events)
    {
        $events->listen('eloquent.booted:*', [$this, 'override']);
    }

    public function override(string $event, $model)
    {
        $class = Str::replaceFirst('eloquent.booted: ', null, $event);

        if (is_array($model)) {
            $model = Arr::first($model);
        }

        if (in_array($class, static::$forced['tenant'])) {
            $model->setConnection(config('tenancy.database.tenant-connection-name'));
        }

        if (in_array($class, static::$forced['system'])) {
            $model->setConnection(config('tenancy.database.system-connection-name'));
        }

        if (config('tenancy.database.models-default-to-tenant-connection')) {
            $model->setConnection(config('tenancy.database.tenant-connection-name'));
        }
        if (config('tenancy.database.models-default-to-system-connection')) {
            $model->setConnection(config('tenancy.database.system-connection-name'));
        }
    }

    public static function forceModel($model, string $to)
    {
        static::$forced[$to][] = is_object($model) ? get_class($model) : $model;
    }

    public static function getForcedModels(): array
    {
        return static::$forced;
    }
}
