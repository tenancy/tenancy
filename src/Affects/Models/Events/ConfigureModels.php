<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Affects\Models\Events;

use InvalidArgumentException;
use Tenancy\Identification\Events\Switched;

class ConfigureModels
{
    /**
     * @var Switched
     */
    public $event;

    public function __construct(Switched $event)
    {
        $this->event = $event;
    }

    public static function __callStatic($method, $parameters)
    {
        if(!is_array($models = array_shift($parameters))){
            $models = [$models];
        }
        foreach ($models as $model) {
            if (!class_exists($model)) {
                throw new InvalidArgumentException("$model does not exist");
            }
            forward_static_call([$model, $method], ...$parameters);
        }
    }

    public function __call($method, $parameters)
    {
        if(!is_array($models = array_shift($parameters))){
            $models = [$models];
        }
        foreach ($models as $model) {
            if (!class_exists($model)) {
                throw new InvalidArgumentException("$model does not exist");
            }
            (new $model)->{$method}(...$parameters);
        }
        return $this;
    }
}
