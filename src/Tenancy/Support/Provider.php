<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Support;

use Illuminate\Support\ServiceProvider;

abstract class Provider extends ServiceProvider
{
    public function register()
    {
        $this->runTrait('register');
    }

    public function boot()
    {
        $this->runTrait('boot');
    }

    protected function runTrait(string $runtime)
    {
        $class = static::class;

        foreach (class_uses_recursive($class) as $trait) {
            if (method_exists($class, $method = $runtime.class_basename($trait))) {
                call_user_func([$this, $method]);
            }
        }
    }
}
