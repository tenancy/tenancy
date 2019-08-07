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
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Support;

use Illuminate\Support\ServiceProvider;
use Tenancy\Affects\Contracts\ResolvesAffects;

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
        $this->app->resolving(ResolvesAffects::class, function (ResolvesAffects $resolver) {
            foreach ($this->affects as $affect) {
                $resolver->addAffect($affect);
            }
        });
    }
}
