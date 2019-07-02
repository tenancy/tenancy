<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Providers\Provides;

use Tenancy\Database\Hooks\DatabaseMutation;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;

trait ProvidesHooks
{
    protected $hooks = [
        DatabaseMutation::class,
    ];

    protected function bootProvidesHooks()
    {
        if (count($this->hooks)) {
            $this->app->resolving(ResolvesHooks::class, function (ResolvesHooks $resolver) {
                foreach ($this->hooks as $hook) {
                    $resolver->addHook($hook);
                }
            });
        }
    }
}
