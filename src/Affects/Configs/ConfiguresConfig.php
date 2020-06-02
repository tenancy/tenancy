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

namespace Tenancy\Affects\Configs;

use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Affect;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresConfig extends Affect
{
    use DispatchesEvents;

    public function fire(): void
    {
        /** @var Repository $config */
        $config = resolve(Repository::class);

        $this->events()->dispatch(new Events\ConfigureConfig($this->event, $config));
    }
}
