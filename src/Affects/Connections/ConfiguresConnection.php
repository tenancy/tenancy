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

namespace Tenancy\Affects\Connections;

use Tenancy\Affects\Affect;
use Tenancy\Affects\Connections\Contracts\ResolvesConnections;

class ConfiguresConnection extends Affect
{
    public function fire(): void
    {
        /** @var ResolvesConnections $resolver */
        $resolver = resolve(ResolvesConnections::class);
        $resolver($this->event->tenant);
    }
}
