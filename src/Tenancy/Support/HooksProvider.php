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

namespace Tenancy\Support;

use Illuminate\Support\ServiceProvider;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;

abstract class HooksProvider extends ServiceProvider
{
    /**
     * Lifecycle event hooks. Hooks that run specific logic
     * during Tenant creation, updates or deletion.
     *
     * @var array
     */
    protected $hooks = [];

    public function register()
    {
        $this->app->resolving(ResolvesHooks::class, function (ResolvesHooks $resolver) {
            foreach ($this->hooks as $hook) {
                $resolver->addHook($hook);
            }
        });
    }
}
