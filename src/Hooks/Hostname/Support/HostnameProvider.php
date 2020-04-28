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

namespace Tenancy\Hooks\Hostname\Support;

use Illuminate\Support\Facades\Event;
use Tenancy\Hooks\Hostname\Events;
use Tenancy\Support\Provider;

abstract class HostnameProvider extends Provider
{
    public function register()
    {
        parent::register();

        if ($this->listener) {
            Event::listen(Events\Resolving::class, $this->listener);
        }
    }
}
