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

namespace Tenancy\Support;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Tenancy\Identification\Events\Switched;

abstract class AffectsProvider extends ServiceProvider
{
    /**
     * Listeners that affect the app logic when a tenant
     * is resolved or switched to.
     *
     * @var array
     */
    protected $affects = [];

    public function register()
    {
        foreach ($this->affects as $affect) {
            Event::listen(Switched::class, $affect);
        }
    }
}
